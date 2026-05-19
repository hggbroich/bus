<?php

declare(strict_types=1);

namespace App\Controller\Admin\Settings;

use App\Settings\ImportSettings;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminRoute;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Jbtronics\SettingsBundle\Form\SettingsFormFactoryInterface;
use Jbtronics\SettingsBundle\Manager\SettingsManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ImportSettingsActionController extends AbstractController {

    public function __construct(private readonly SettingsManagerInterface $settingsManager,
                                private readonly SettingsFormFactoryInterface $formFactory,
                                private readonly AdminUrlGenerator $adminUrlGenerator) {

    }


    #[AdminRoute('/settings/import', name: 'import_settings')]
    public function __invoke(Request $request): Response {
        $settings = $this->settingsManager->createTemporaryCopy(ImportSettings::class);
        $builder = $this->formFactory->createSettingsFormBuilder($settings);
        $form = $builder->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->settingsManager->mergeTemporaryCopy($settings);
            $this->settingsManager->save();

            $this->addFlash('success', 'settings.success');
            return $this->redirect(
                $this->adminUrlGenerator->setRoute('admin_import_settings')->generateUrl()
            );
        }

        return $this->render('admin/form.html.twig', [
            'form' => $form->createView(),
            'header' => 'settings.import.label',
            'action' => 'actions.save'
        ]);
    }
}
