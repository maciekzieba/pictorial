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
use Mz\PictorialBundle\Service\VisitService;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PawelLen\DataTablesListing\Filter\FilterBuilderInterface;
use PawelLen\DataTablesListing\Column\ColumnBuilderInterface;
use PawelLen\DataTablesListing\Type\AbstractType;


class VisitListing extends AbstractType
{


    /** @var  VisitService */
    protected $visitService;

    /**
     * @param VisitService $visitService
     */
    public function __construct(VisitService $visitService)
    {
        $this->visitService = $visitService;
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
            ->add('number', 'column', array(
                'label' => 'Numer',
            ))
            ->add('visitDate', 'column', array(
                'label' => 'Data wizyty',
            ))
            ->add('package', 'column', array(
                'label' => 'Pakiet',
                'property' => 'package.fullName'
            ))
            ->add('photoOwner', 'column', array(
                'label' => 'Zdjęcia',
                'property' => 'photoOwner.fullName'
            ))
            ->add('firstname', 'column', array(
                'label' => 'Imię',
            ))
            ->add('lastname', 'column', array(
                'label' => 'Nazwisko',
            ))
            ->add('city', 'column', array(
                'label' => 'Miasto',
            ))
            ->add('district', 'column', array(
                'label' => 'Dzielnica',
            ))
            ->add('cardNumber', 'column', array(
                'label' => 'Numer karty',
            ))
            ->add('realizationStatus', 'column', array(
                'label' => 'Status realizacji',
                'callback' => function ($value) {
                    return $this->visitService->getRealizationStatusesText($value);
                }
            ))
            ->add('paymentStatus', 'column', array(
                'label' => 'Status płatności',
                'callback' => function ($value) {
                    return $this->visitService->getPaymentStatusesText($value);
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
                $builder->select('v, p, po')
                    ->from('MzPictorialBundle:Visit', 'v')
                    ->leftJoin('v.package', 'p')
                    ->leftJoin('v.photoOwner', 'po');

            },
        ));
    }


    public function getName()
    {
        return 'visit_list';
    }

}
