<?php

namespace App\Import\Cities;

use OpenPlzApi\DE\ApiClientForGermany;
use OpenPlzApi\DE\Locality;
use OpenPlzApi\ReadOnlyPagedList;

class OpenPlzApiClient extends ApiClientForGermany {

    /**
     * @param string $key
     * @param int $pageIndex
     * @param int $pageSize
     * @return ReadOnlyPagedList<Locality>
     */
    public function getLocalitiesByFederalState(string $key, int $pageIndex = 1, int $pageSize = 50): ReadOnlyPagedList {
        $url = $this->createUrl("de/FederalStates/{$key}/Localities");
        $params = [
            'page' => $pageIndex,
            'pageSize' => $pageSize
        ];
        $nextPage = function () use ($key, $pageIndex, $pageSize)  {
            return $this->getLocalitiesByFederalState($key, $pageIndex + 1, $pageSize);
        };

        return $this->getPage($url, $params, Locality::class, $nextPage);
    }
}
