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


class UserListing extends AbstractType
{

    public function buildFilters(FilterBuilderInterface $builder, array $options)
    {
        $builder->
            add('name', 'search', array(
                'label' => 'Imie i nazwisko',
                'filter' => array(
                    'expression' => "CONCAT(CONCAT(u.firstname , ' '), u.lastname)  LIKE ?",
                    'eval' => '%like%'
                )
            ))
            ->add('email', 'search', array(
                'label' => 'Email',
                'filter' => array(
                    'expression' => 'u.email LIKE ?',
                    'eval' => '%like%'
                )
            ))
        ;

    }


    public function buildColumns(ColumnBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', 'column', array(
                'label' => 'Id',
            ))
            ->add('createdAt', 'column', array(
                'label' => 'Utworzony',
            ))
            ->add('username', 'column', array(
                'label' => 'Email',
            ))
            ->add('firstname', 'column', array(
                'label' => 'Imię',
            ))
            ->add('lastname', 'column', array(
                'label' => 'Nazwisko',
            ))

            ->add('roles', 'column', array(
                'label' => 'Role',
                'align' => 'center',
                'callback' => function ($value) {
                    $roles = array();
                    foreach ($value as $role) {
                        $roles[] = $role;
                    }
                    $rolesString = \implode(', ', $roles);

                    return $rolesString;
                }
            ))
        ;
    }


    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'query_builder' => function (QueryBuilder $builder) {
                $builder->select('u')
                    ->from('MzPictorialBundle:User', 'u');

            },
        ));
    }


    public function getName()
    {
        return 'user_list';
    }

}
