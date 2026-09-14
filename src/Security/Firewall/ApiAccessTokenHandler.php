<?php

namespace App\Security\Firewall;

use App\Repository\ApiAccessTokenRepositoryInterface;
use Override;
use SensitiveParameter;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

readonly class ApiAccessTokenHandler implements AccessTokenHandlerInterface {

    public function __construct(
        private ApiAccessTokenRepositoryInterface $repository
    ) {

    }

    #[Override]
    public function getUserBadgeFrom(#[SensitiveParameter] string $accessToken): UserBadge {
        $accessToken = $this->repository->findOneByAccessToken($accessToken);

        if($accessToken === null) {
            throw new BadCredentialsException('Invalid API token.');
        }

        return new UserBadge($accessToken->getUser()->getUuid());
    }
}
