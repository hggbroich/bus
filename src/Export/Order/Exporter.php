<?php

namespace App\Export\Order;

use App\Entity\Gender;
use App\Repository\FareLevelRepositoryInterface;
use App\Repository\OrderRepositoryInterface;
use App\Repository\TicketRepositoryInterface;
use App\Settings\ExportSettings;
use App\Settings\ImportSettings;
use App\Settings\ValueDataType;
use League\Csv\Reader;
use League\Csv\Writer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

readonly class Exporter {
    public function __construct(
        private OrderRepositoryInterface     $orderRepository,
        private ExportSettings               $exportSettings,
        private FareLevelRepositoryInterface $fareLevelRepository, private ImportSettings $importSettings
    ) {

    }

    private function fill(array &$row, array $headers, string $column, mixed $value): void {
        $columnIdx = array_search($column, $headers);

        if($columnIdx === false) {
            return;
        }

        $row[$columnIdx] = $value;
    }

    public function export(ExportRequest $request): Response {
        $csv = Reader::from($request->csv->getRealPath());
        $csv->setHeaderOffset(0);
        $csv->setDelimiter($request->delimiter);
        $csv->setEscape('');

        $headers = $csv->getHeader();

        $csv = Writer::from('php://temp', 'w');
        $csv->setDelimiter($request->delimiter);
        $csv->setEscape('');
        $csv->setOutputBOM(Writer::BOM_UTF8);

        $csv->insertOne($headers);

        foreach($this->orderRepository->findAllRange($request->startDate, $request->endDate) as $order) {
            $row = array_fill(0, count($headers), ''); // create row with empty columns/cells

            // Fill order columns
            $this->fill($row, $headers, $request->customerIdHeader, $order->getBusCompanyCustomerId());
            $this->fill($row, $headers, $request->firstnameHeader, $order->getFirstname());
            $this->fill($row, $headers, $request->lastnameHeader, $order->getLastname());
            $this->fill($row, $headers, $request->streetHeader, $order->getStreet());
            [$number, $suffix ] = $this->splitHouseNumberAndSuffix($order->getHouseNumber());
            $this->fill($row, $headers, $request->houseNumberHeader, $number);
            $this->fill($row, $headers, $request->houseNumberSuffixHeader, $suffix);
            $this->fill($row, $headers, $request->plzHeader, $order->getPlz());
            $this->fill($row, $headers, $request->cityHeader, $order->getCity());
            $this->fill($row, $headers, $request->phoneNumberHeader, $order->getDepositorPhoneNumber());
            $this->fill($row, $headers, $request->birthdayHeader, $order->getBirthday()?->format('d.m.Y'));
            $this->fill($row, $headers, $request->genderHeader, $this->getGender($order->getGender()));

            $this->fill($row, $headers, $request->ibanHeader, $order->getEncryptedIban());
            $this->fill($row, $headers, $request->depositorFirstnameHeader, $order->getDepositorFirstname());
            $this->fill($row, $headers, $request->depositorLastnameHeader, $order->getDepositorLastname());
            $this->fill($row, $headers, $request->depositorStreetHeader, $order->getDepositorStreet());
            [$number, $suffix ] = $this->splitHouseNumberAndSuffix($order->getDepositorHouseNumber());
            $this->fill($row, $headers, $request->depositorHouseNumberHeader, $number);
            $this->fill($row, $headers, $request->depositorHouseNumberSuffixHeader, $suffix);
            $this->fill($row, $headers, $request->depositorPLZHeader, $order->getDepositorPLZ());
            $this->fill($row, $headers, $request->depositorCityHeader, $order->getDepositorCity());
            $this->fill($row, $headers, $request->depositorCountryHeader, $order->getDepositorCountry()?->getIsoCode());
            $this->fill($row, $headers, $request->depositorBirthdayHeader, $order->getDepositorBirthday()?->format('d.m.Y'));

            // Fill external id
            $this->fill($row, $headers, $request->studentIdHeader, $this->exportSettings->studentIdPrefix . $order->getStudent()->getExternalId());

            // Fill ticket
            $this->fill($row, $headers, $request->ticketHeader, $order->getTicket()->getExternalIdForPaymentInterval($order->getPaymentInterval()));

            // Fill fare level
            $this->fill($row, $headers, $request->fareLevelHeader, $order->getFareLevel()->getLevel());

            // Export default columns
            foreach($this->exportSettings->additionalColumns as $keyValuePair) {
                $value = $keyValuePair->value;

                if($keyValuePair->type === ValueDataType::Integer) {
                    $value = intval($value);
                } elseif($keyValuePair->type === ValueDataType::Float) {
                    $value = floatval($value);
                }

                $this->fill($row, $headers, $keyValuePair->key, $value);
            }

            $csv->insertOne($row);
        }


        $response = new Response($csv->toString());
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'orders.csv'));

        return $response;
    }

    /**
     * @return array{ string, string|null } First index is house number and last is suffix (or null if no suffix is present)
     * @return array{ null, null } If no house number is provided, both values are present but null
     */
    private function splitHouseNumberAndSuffix(string|null $houseNumber): array {
        if(empty($houseNumber)) {
            return [ null, null ];
        }

        preg_match('/([0-9]+)\s*([a-zA-Z]*)/i', $houseNumber, $matches);
        $number = $matches[1];
        $suffix = $matches[2];

        if(empty($suffix)) {
            $suffix = null;
        }

        return [
            $number,
            $suffix
        ];
    }

    private function getGender(Gender $gender): string {
        return match($gender) {
            Gender::Male => 'm',
            Gender::Female => 'f',
            Gender::Divers => 'd',
            Gender::Other => 'o',
        };
    }
}
