<?php

namespace App\Form;

use App\Entity\School;
use App\Settings\OrderSettings;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function is_callable;

class SchoolType extends EntityType {


    public function __construct(
        public readonly OrderSettings $orderSettings,
        ManagerRegistry $registry
    ) {
        parent::__construct($registry);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        parent::configureOptions($resolver);

        $resolver->setDefault('label', 'label.school');
        $resolver->setDefault('class', School::class);
        $resolver->setDefault('multiple', false);
        $resolver->setDefault('expanded', false);
        $resolver->setDefault('exclude_own_school', true);

        $resolver->setDefault('query_builder', function(EntityRepository $repository): QueryBuilder {
            return $repository->createQueryBuilder('s')
                ->orderBy('s.name', 'ASC');
        });

        $resolver->setNormalizer('query_builder', function (Options $options, $queryBuilder) {
            if (is_callable($queryBuilder)) {
                $queryBuilder = $queryBuilder($options['em']->getRepository($options['class']));
            }

            if($queryBuilder !== null && $options['exclude_own_school'] === true && $this->orderSettings->school !== null) {
                $queryBuilder->andWhere('s.id != :school')
                    ->setParameter('school', $this->orderSettings->school->getId());
            }

            return $queryBuilder;
        });
    }
}
