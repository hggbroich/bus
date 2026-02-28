<?php

namespace App\Menu;

use App\Menu\SettingsMenuBuilder;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class AdminMenuBuilder extends AbstractMenuBuilder {

    public function adminMenu(array $options): ItemInterface {
        $root = $this->factory->createItem('root')
            ->setChildrenAttributes([
                'class' => 'navbar-nav float-lg-right'
            ]);

        $menu = $root->addChild('admin', [
            'label' => ''
        ])
            ->setExtra('icon', 'fa fa-cogs')
            ->setAttribute('title', $this->translator->trans('administration.label'))
            ->setExtra('menu', 'admin')
            ->setExtra('menu-container', '#submenu')
            ->setExtra('pull-right', true);

        if($this->authorizationChecker->isGranted('ROLE_ADMIN')
            || $this->authorizationChecker->isGranted('ROLE_ORDER_ADMIN')
            || $this->authorizationChecker->isGranted('ROLE_COUNTRY_ADMIN')) {
            $menu->addChild('administration.label', [
                'route' => 'admin'
            ]);
        }

        if($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $menu->addChild('messenger.label', [
                'route' => 'zenstruck_messenger_monitor_dashboard'
            ])
                ->setExtra('icon', 'fas fa-envelope-open-text');

            $menu->addChild('logs.label', [
                'route' => 'admin_logs'
            ])
                ->setExtra('icon', 'fas fa-clipboard-list');
        }

        return $root;
    }
}
