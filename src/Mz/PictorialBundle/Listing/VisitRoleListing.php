<?php

/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 21.07.14
 * Time: 09:05
 */

namespace Mz\PictorialBundle\Listing;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PawelLen\DataTablesListing\Filter\FilterBuilderInterface;
use PawelLen\DataTablesListing\Column\ColumnBuilderInterface;
use PawelLen\DataTablesListing\Type\AbstractType;


class VisitRoleListing extends AbstractType
{


    public function buildFilters(FilterBuilderInterface $builder, array $options)
    {

    }

    public function buildColumns(ColumnBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'column', array(
                'label' => 'Name',
            ))
            ->add('actions', 'column', array(
                'label' => 'Akcje',
                'order_by' => false
            ))
        ;
    }


    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'query_builder' => function (QueryBuilder $builder) {
                $builder->select('c')
                    ->from('MzPictorialBundle:VisitRole', 'c');

            },
        ));
    }


    public function getName()
    {
        return 'visit_role_list';
    }

}
