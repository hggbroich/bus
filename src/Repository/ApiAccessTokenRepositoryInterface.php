<?php

namespace App\Repository;

use App\Entity\ApiAccessToken;

interface ApiAccessTokenRepositoryInterface {
    public function findOneByAccessToken(string $accessToken): ?ApiAccessToken;

    public function persist(ApiAccessToken $apiAccessToken): void;

    public function remove(ApiAccessToken $apiAccessToken): void;
}
