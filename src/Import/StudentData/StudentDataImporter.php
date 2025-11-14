<?php

namespace App\Import\StudentData;

use App\Entity\School;
use App\Entity\Student;
use App\Import\CsvHelperTrait;
use App\Repository\SchoolRepositoryInterface;
use App\Repository\StudentRepositoryInterface;
use App\Utils\ArrayUtils;
use League\Csv\Reader;

readonly class StudentDataImporter {

    use CsvHelperTrait;

    public function __construct(
        private StudentRepositoryInterface $studentRepository,
        private SchoolRepositoryInterface $schoolRepository
    ) { }

    public function import(ImportRequest $request): int {
        $csv = Reader::from($request->csv->getRealPath());
        $csv->setHeaderOffset(0);
        $csv->setDelimiter($request->delimiter);
        $csv->setEscape('');

        /** @var array<string, Student> $existingStudents */
        $existingStudents = ArrayUtils::createArrayWithKeys(
            $this->studentRepository->findAll(),
            fn(Student $student): string => $student->getExternalId()
        );

        /** @var array<int, School> $schools */
        $schools = ArrayUtils::createArrayWithKeys(
            $this->schoolRepository->findAll(),
            fn(School $school): int => $school->getSchoolNumber()
        );

        $this->studentRepository->beginTransaction();
        $updated = 0;
        foreach($csv->getRecords() as $record) {
            $id = $this->getString($record[$request->studentIdHeader]);
            $student = $existingStudents[$id] ?? null;

            if(!$student instanceof Student) {
                continue;
            }

            $schoolNumber = $this->getIntOrNull(
                $this->getString($record[$request->publicSchoolHeader])
            );

            if($schoolNumber !== null && array_key_exists($schoolNumber, $schools)) {
                $school = $schools[$schoolNumber];
                $student->setPublicSchool($school);
            }

            $distanceToPublicSchool = $this->getFloatOrNull(
                $this->getStringOrNull($record[$request->distancePublicSchoolHeader])
            );

            if($distanceToPublicSchool !== null && $distanceToPublicSchool > 0) {
                $student->setDistanceToPublicSchool($distanceToPublicSchool);
                $student->setConfirmedDistanceToPublicSchool($distanceToPublicSchool);
            }

            $distanceToSchool = $this->getFloatOrNull(
                $this->getStringOrNull($record[$request->distanceSchoolHeader])
            );

            if($distanceToSchool !== null && $distanceToSchool > 0) {
                $student->setDistanceToSchool($distanceToSchool);
                $student->setConfirmedDistanceToSchool($distanceToSchool);
            }

            $this->studentRepository->persist($student);
            $updated++;
        }

        $this->studentRepository->commit();
        return $updated;
    }
}
