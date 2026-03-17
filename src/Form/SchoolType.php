<?php

namespace App\Form;

use App\Entity\School;
use App\Settings\OrderSettings;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        $resolver->setDefault('query_builder', function(EntityRepository $repository): QueryBuilder {
            $qb = $repository->createQueryBuilder('s')
                ->orderBy('s.name', 'ASC');

            if($this->orderSettings->school !== null) {
                $qb->andWhere('s.id != :school')
                    ->setParameter('school', $this->orderSettings->school->getId());
            }

            return $qb;
        });
    }
}
