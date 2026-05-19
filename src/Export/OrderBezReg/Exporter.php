<?php

namespace App\Export\OrderBezReg;

use App\Repository\OrderRepositoryInterface;
use App\Repository\StudentRepositoryInterface;
use League\Csv\Bom;
use League\Csv\Writer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

readonly class Exporter {
    public function __construct(
        private StudentRepositoryInterface $studentRepository,
        private OrderRepositoryInterface $orderRepository,
    ) {

    }

    public function export(ExportRequest $request): Response {
        $csv = Writer::from('php://temp', 'w');
        $csv->setDelimiter($request->delimiter);
        $csv->setEscape('');
        $csv->setOutputBOM(Bom::Utf8);

        $headers = [
            'Schueler_ID',
            'Entf. öffentliche Schule',
            'Entf. Schule',
            'Haltestelle',
            'SGBXIII',
            'Ticket',
            'Name öffentliche Schule',
            'Schulnummer öffentliche Schule',
        ];

        $csv->insertOne($headers);

        foreach($this->studentRepository->findAllByStatus($request->status) as $student) {
            $order = $this->orderRepository->findForStudentInRange($student, $request->startDate, $request->endDate);

            $csv->insertOne([
                $student->getExternalId(),
                $student->getConfirmedDistanceToPublicSchool(),
                $student->getConfirmedDistanceToSchool(),
                $student->getStop()?->getName(),
                $student->isSgb12() ? 'true' : 'false',
                $order?->getTicket()?->getName(),
                $student->getPublicSchool()?->getName(),
                $student->getPublicSchool()?->getSchoolNumber()
            ]);
        }

        $response = new Response($csv->toString());
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'bezreg.csv'));

        return $response;
    }
}
