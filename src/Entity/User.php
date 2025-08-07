<?php

namespace App\Entity;

use Override;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface {

    #[Override]
    public function getRoles(): array {
        // TODO: Implement getRoles() method.
    }

    #[Override]
    public function eraseCredentials(): void {
        // TODO: Implement eraseCredentials() method.
    }

    #[Override]
    public function getUserIdentifier(): string {
        // TODO: Implement getUserIdentifier() method.
    }
}
