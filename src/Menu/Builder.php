<?php

namespace App\Menu;

use App\Security\Voter\OrderVoter;
use App\Security\Voter\ProfileVoter;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

readonly class Builder {
    public function __construct(private FactoryInterface $factory, private AuthorizationCheckerInterface $authorizationChecker)
    {
    }

    public function mainMenu(array $options): ItemInterface {
        $menu = $this->factory->createItem('root')
            ->setChildrenAttribute('class', 'navbar-nav me-auto');

        $menu->addChild('dashboard.label', [
            'route' => 'dashboard'
        ])
            ->setExtra('icon', 'fa fa-home');

        if($this->authorizationChecker->isGranted(ProfileVoter::ViewList)) {
            $menu->addChild('profile.label', [
                'route' => 'profile'
            ])
                ->setExtra('icon', 'fa-solid fa-address-card');
        }

        if($this->authorizationChecker->isGranted(OrderVoter::ANY)) {
            $menu->addChild('orders.label', [
                'route' => 'orders',
            ])
                ->setExtra('icon', 'fa fa-shopping-basket');
        }

        return $menu;
    }
}
