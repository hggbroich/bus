<?php

namespace App\Export\Order;

use App\Entity\Gender;
use App\Repository\OrderRepositoryInterface;
use App\Settings\ExportSettings;
use App\Settings\ValueDataType;
use League\Csv\Bom;
use League\Csv\Reader;
use League\Csv\Writer;
use League\Flysystem\FilesystemOperator;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberType;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

readonly class Exporter {



    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private ExportSettings  $exportSettings,
        private PhoneNumberUtil $phoneNumberUtil,
        private CacheManager $cacheManager
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
        $csv->setOutputBOM(Bom::Utf8);

        if($request->includeOrderId) {
            $headers[] = 'Order_ID';
        }

        $csv->insertOne($headers);

        if($request->cacheFile && $request->csv instanceof UploadedFile) {
            $this->cacheManager->writeFile($csv->toString());
        }

        foreach($this->orderRepository->findAllRange($request->startDate, $request->endDate) as $order) {
            if($order->isIncorrect()) { // no not call validator here as it is very slow for many orders!!
                continue; // do not export invalid orders
            }

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

            $this->fill($row, $headers, $request->phoneNumberHeader, $this->normalizePhoneNumber($order->getDepositorPhoneNumber(), PhoneNumberType::FIXED_LINE));
            $this->fill($row, $headers, $request->mobilePhoneNumberHeader, $this->normalizePhoneNumber($order->getDepositorPhoneNumber(), PhoneNumberType::MOBILE));

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

            // Export Order_ID
            if($request->includeOrderId) {
                $this->fill($row, $headers, 'Order_ID', $order->getId());
            }

            $csv->insertOne($row);
        }

        $response = new Response($csv->toString());
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'orders.csv'));

        return $response;
    }

    /**
     * @return array{ string, string|null } First index is house number and last is suffix (or null if no suffix is
     *     present)
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

    private function fillPhoneNumber(string $phoneNumber, bool $isMobile, string $headerName): void {
        try {
            $parsedPhoneNumber = $this->phoneNumberUtil->parse($phoneNumber, defaultRegion: 'DE');


        } catch (NumberParseException) {

        }
    }

    private function normalizePhoneNumber(string $phoneNumber, PhoneNumberType $type): ?string {
        try {
            $parsedPhoneNumber = $this->phoneNumberUtil->parse($phoneNumber, defaultRegion: 'DE');

            if($this->phoneNumberUtil->getNumberType($parsedPhoneNumber) === $type) {
                return $this->phoneNumberUtil->format($parsedPhoneNumber, PhoneNumberFormat::E164);
            }

            return null;
        } catch (NumberParseException) {
            return $phoneNumber;
        }
    }

    private function getGender(Gender $gender): string {
        return match($gender) {
            Gender::Male => 'M',
            Gender::Female => 'W',
            Gender::Divers => 'D',
            Gender::Other => 'O',
        };
    }
}
