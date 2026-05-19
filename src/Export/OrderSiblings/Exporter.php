<?php

namespace App\Export\OrderSiblings;

use App\Repository\OrderRepositoryInterface;
use League\Csv\Bom;
use League\Csv\Writer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

readonly class Exporter {
    public function __construct(
        private OrderRepositoryInterface $orderRepository
    ) {

    }

    public function export(ExportRequest $request): Response {
        $csv = Writer::from('php://temp', 'w');
        $csv->setDelimiter($request->delimiter);
        $csv->setEscape('');
        $csv->setOutputBOM(Bom::Utf8);

        $headers = [
            'Bestellung_ID',
            'Bestellung_Schueler_ID',
            'GK_Schueler_ID',
            'GK_Schulnummer',
            'GK_Vorname',
            'GK_Nachname',
            'GK_GebDatum'
        ];

        $csv->insertOne($headers);

        foreach($this->orderRepository->findAllRange($request->startDate, $request->endDate) as $order) {
            foreach($order->getSiblings() as $sibling) {
                $csv->insertOne([
                    $order->getId(),
                    $order->getStudent()->getExternalId(),
                    $sibling->getStudentAtSchool()?->getExternalId(),
                    $sibling->getSchool()?->getSchoolNumber(),
                    $sibling->getFirstName(),
                    $sibling->getLastName(),
                    $sibling->getBirthday()?->format('d.m.Y')
                ]);
            }
        }

        $response = new Response($csv->toString());
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'siblings.csv'));

        return $response;
    }
}
