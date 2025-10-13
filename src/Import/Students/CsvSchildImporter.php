<?php

namespace App\Import\Students;

use App\Entity\Student;
use App\Import\CsvHelperTrait;
use App\Import\ImportResult;
use App\Repository\StudentRepositoryInterface;
use App\Utils\ArrayUtils;
use League\Csv\Reader;

class CsvSchildImporter {

    use CsvHelperTrait;

    public function __construct(private readonly StudentRepositoryInterface $studentRepository) { }

    public function import(ImportRequest $request): ImportResult {
        $csv = Reader::createFromPath($request->csv->getRealPath());
        $csv->setHeaderOffset(0);
        $csv->setDelimiter($request->delimiter);
        $csv->setEscape('');

        $existingStudents = ArrayUtils::createArrayWithKeys(
            $this->studentRepository->findAll(),
            fn(Student $student) => $student->getExternalId()
        );

        $records = $csv->getRecords();

        $added = 0;
        $updated = 0;
        $removed = 0;

        $targetStudentIds = [ ];

        $this->studentRepository->beginTransaction();

        foreach($records as $record) {
            $id = $record[$request->idHeader];
            $targetStudentIds[] = $id;

            $student = $existingStudents[$id] ?? null;

            if($student === null) {
                $student = new Student();
                $student->setExternalId($id);

                $added++;
            } else {
                $updated++;
            }

            $student->setStatus($this->getStatus($this->getString($record[$request->statusHeader])));
            $student->setFirstname($this->getString($record[$request->firstnameHeader]));
            $student->setLastname($this->getString($record[$request->lastnameHeader]));
            $student->setGrade($this->getStringOrNull($record[$request->gradeHeader]));
            $student->setEmail($this->getStringOrNull($record[$request->emailHeader]));
            $student->setStreet($this->getStringOrNull($record[$request->streetHeader]));
            $student->setHouseNumber($this->getString($record[$request->houseNumberHeader]));
            $student->setPlz($this->getIntOrNull($record[$request->plzHeader]));
            $student->setCity($this->getStringOrNull($record[$request->cityHeader]));
            $student->setEntranceDate($this->getDateTime($record[$request->entranceDateHeader]));
            $student->setLeaveDate($this->getDateTimeOrNull($record[$request->leaveDateHeader]));
            $student->setBirthday($this->getDateTime($record[$request->birthdayHeader]));

            $this->studentRepository->persist($student);
        }

        foreach($existingStudents as $externalId => $student) {
            if(!in_array($externalId, $targetStudentIds)) {
                $this->studentRepository->remove($student);
            }
        }

        $this->studentRepository->commit();

        return new ImportResult($added, $updated, $removed);
    }

    private function getStatus(?int $status): string {
        return match ($status) {
            0 => 'Neuaufnahme',
            1 => 'Warteliste',
            2 => 'Aktive',
            3 => 'Beurlaubt',
            6 => 'Externe',
            8 => 'Abitur',
            9 => 'Abgänger',
            default => 'Unbekannt',
        };
    }
}
