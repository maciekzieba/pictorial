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
use Mz\PictorialBundle\Service\PackageService;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PawelLen\DataTablesListing\Filter\FilterBuilderInterface;
use PawelLen\DataTablesListing\Column\ColumnBuilderInterface;
use PawelLen\DataTablesListing\Type\AbstractType;


class PackageListing extends AbstractType
{


    /** @var  PackageService */
    protected $packageService;

    /**
     * @param PackageService $packageService
     */
    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }

    public function buildFilters(FilterBuilderInterface $builder, array $options)
    {
        $builder->
            add('id', 'search', array(
                'label' => 'Id',
                'filter' => array(
                    'expression' => "p.id LIKE ?",
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
            ->add('validityDate', 'column', array(
                'label' => 'Data pakietu',
            ))
            ->add('visitsQuantity', 'column', array(
                'label' => 'Liczba wizyt',
            ))
            ->add('priceNet', 'column', array(
                'label' => 'Cena netto',
            ))
            ->add('priceNetPerVisit', 'column', array(
                'label' => 'Cena netto per wizyta',
            ))
            ->add('status', 'column', array(
                'label' => 'Status',
                'callback' => function ($value) {
                    return $this->packageService->getStatusText($value);
                }
            ))
            ->add('actions', 'column', array(
                'label' => 'Akcje'
            ))
        ;
    }


    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'query_builder' => function (QueryBuilder $builder) {
                $builder->select('p')
                    ->from('MzPictorialBundle:Package', 'p');

            },
        ));
    }


    public function getName()
    {
        return 'package_list';
    }

}
