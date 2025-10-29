<?php

namespace App\Import\BusCompanyData;

use App\Entity\Student;
use App\Entity\TicketPaymentInterval;
use App\Import\CsvHelperTrait;
use App\Repository\StudentRepositoryInterface;
use App\Repository\TicketPaymentIntervalRepositoryInterface;
use App\Settings\ExportSettings;
use App\Utils\ArrayUtils;
use League\Csv\Reader;

readonly class BusCompanyDataImporter {

    use CsvHelperTrait;

    public function __construct(
        private ExportSettings $exportSettings,
        private TicketPaymentIntervalRepositoryInterface $ticketPaymentIntervalRepository,
        private StudentRepositoryInterface $studentRepository
    ) {

    }

    public function import(ImportBusCompanyDataRequest $request): int {
        $csv = Reader::from($request->csv->getRealPath());
        $csv->setHeaderOffset(0);
        $csv->setDelimiter($request->delimiter);
        $csv->setEscape('');

        $studentIds = [ ];

        /** @var array<string, TicketPaymentInterval> $ticketPaymentIntervals */
        $ticketPaymentIntervals = ArrayUtils::createArrayWithKeys(
            $this->ticketPaymentIntervalRepository->findAll(),
            fn(TicketPaymentInterval $ticketPaymentInterval): string => $ticketPaymentInterval->getExternalId()
        );

        foreach($csv->getRecords() as $record) {
            $id = ltrim($this->getString($record[$request->studentIdHeader]), $this->exportSettings->studentIdPrefix);
            $studentIds[] = $id;
        }

        /** @var array<string, Student> $students */
        $students = ArrayUtils::createArrayWithKeys(
            $this->studentRepository->findAllByEmailOrId($studentIds),
            fn(Student $student): string => $student->getExternalId()
        );

        $this->studentRepository->beginTransaction();
        $updatedStudents = 0;

        foreach($csv->getRecords() as $record) {
            $id = ltrim($this->getString($record[$request->studentIdHeader]), $this->exportSettings->studentIdPrefix);

            $busCompanyCustomerId = $this->getStringOrNull($record[$request->customerIdHeader]);
            $ticketId = $this->getStringOrNull($record[$request->ticketHeader]);
            $ticketPaymentInterval = $ticketPaymentIntervals[$ticketId] ?? null;

            $student = $students[$id] ?? null;

            if($student === null) {
                continue;
            }

            if(!empty($busCompanyCustomerId)) {
                $student->setBusCompanyCustomerId($busCompanyCustomerId);
            }

            if($ticketPaymentInterval !== null) {
                $student->setPaymentInterval($ticketPaymentInterval->getPaymentInterval());
            }

            $this->studentRepository->persist($student);
            $updatedStudents++;
        }

        $this->studentRepository->commit();
        return $updatedStudents;
    }
}
