<?php

/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 21.07.14
 * Time: 09:05
 */

namespace Mz\PictorialBundle\Listing;

use Doctrine\ORM\QueryBuilder;
use Mz\PictorialBundle\Entity\User;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PawelLen\DataTablesListing\Filter\FilterBuilderInterface;
use PawelLen\DataTablesListing\Column\ColumnBuilderInterface;
use PawelLen\DataTablesListing\Type\AbstractType;


class UserPricelistListing extends AbstractType
{

    /** @var  User */
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }


    public function buildFilters(FilterBuilderInterface $builder, array $options)
    {

    }

    public function buildColumns(ColumnBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'column', array(
                'label' => 'Name',
                'property' => 'visitRole.name'
            ))
            ->add('price', 'column', array(
                'label' => 'Cena'
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
                $builder->select('p')
                    ->from('MzPictorialBundle:Pricelist', 'p')
                    ->where('p.user = :userId')->setParameter('userId', $this->user->getId());

            },
        ));
    }


    public function getName()
    {
        return 'pricelist_list';
    }

}
