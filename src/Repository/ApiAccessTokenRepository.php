<?php

namespace App\Repository;

use App\Entity\ApiAccessToken;
use Override;

class ApiAccessTokenRepository extends AbstractRepository implements ApiAccessTokenRepositoryInterface {

    #[Override]
    public function findOneByAccessToken(string $accessToken): ?ApiAccessToken {
        return $this->em->getRepository(ApiAccessToken::class)->findOneBy(['accessToken' => $accessToken]);
    }

    #[Override]
    public function persist(ApiAccessToken $apiAccessToken): void {
        $this->em->persist($apiAccessToken);
        $this->em->flush();
    }

    #[Override]
    public function remove(ApiAccessToken $apiAccessToken): void {
        $this->em->remove($apiAccessToken);
        $this->em->flush();
    }
}
