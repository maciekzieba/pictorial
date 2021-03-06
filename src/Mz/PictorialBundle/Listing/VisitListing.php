<?php

/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 21.07.14
 * Time: 09:05
 */

namespace Mz\PictorialBundle\Listing;

use Doctrine\ORM\EntityRepository;
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
        $builder
            ->add('query', 'search', array(
                'label' => 'Szukaj',
                'filter' => array(
                    'expression' => "v.number LIKE ? OR v.city LIKE ? OR v.district LIKE ? OR v.firstname LIKE ? OR v.lastname LIKE ?",
                    'eval' => '%like%'
                )
            ))
            ->add('realizationStatus', 'choice', array(
                'label' => 'Status realizacji',
                'choices' => $this->visitService->getRealizationStatuses(),
                'placeholder' => '',
                'filter' => array(
                    'expression' => "v.realizationStatus LIKE ?",
                )

            ))
            ->add('paymentStatus', 'choice', array(
                'label' => 'Status płatności',
                'choices' => $this->visitService->getPaymentStatuses(),
                'placeholder' => '',
                'filter' => array(
                    'expression' => "v.paymentStatus LIKE ?",
                )
            ))
            ->add('owner', 'entity', array(
                'label' => 'Właściciel',
                'class' => 'MzPictorialBundle:User',
                'property' => 'fullName',
                'placeholder' => '',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles LIKE :roles')
                        ->setParameter('roles', '%ADMIN%')
                        ->orderBy('u.id')
                        ;
                },
                'filter' => array(
                    'expression' => "v.owner = ?",
                )
            ))
            ->add('package', 'entity', array(
                'label' => 'Pakiet',
                'class' => 'MzPictorialBundle:Package',
                'property' => 'fullName',
                'placeholder' => '',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ;
                },
                'filter' => array(
                    'expression' => "v.package = ?",
                )
            ))
        ;

    }

    public function buildColumns(ColumnBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', 'column', array(
                'label' => 'Numer',
            ))
            ->add('visitDate', 'column', array(
                'label' => 'Data wizyty',
            ))
            ->add('package', 'column', array(
                'label' => 'Pakiet',
                'property' => 'package.fullName',
                'order_by' => false
            ))
            ->add('owner', 'column', array(
                'label' => 'Właścicel',
                'order_by' => false,
                'property' => 'owner.fullName'
            ))
            ->add('cardNumber', 'column', array(
                'label' => 'Numer karty',
                'order_by' => false
            ))
            ->add('firstname', 'column', array(
                'label' => 'Imię',
                'order_by' => false
            ))
            ->add('lastname', 'column', array(
                'label' => 'Nazwisko',
                'order_by' => false
            ))
            ->add('city', 'column', array(
                'label' => 'Miasto',
                'order_by' => false,
            ))
            ->add('district', 'column', array(
                'label' => 'Dzielnica',
                'order_by' => false,
            ))
            ->add('realizationStatus', 'column', array(
                'label' => 'Realizacja',
                'order_by' => false,
                'callback' => function ($value) {
                    return $this->visitService->getRealizationStatusesText($value);
                }
            ))
            ->add('paymentStatus', 'column', array(
                'label' => 'Płatność',
                'order_by' => false,
                'callback' => function ($value) {
                    return $this->visitService->getPaymentStatusesText($value);
                }
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
            'page_length' => 100,
            'query_builder' => function (QueryBuilder $builder) {
                $builder->select('v, p, o')
                    ->from('MzPictorialBundle:Visit', 'v')
                    ->leftJoin('v.package', 'p')
                    ->leftJoin('v.owner', 'o');

            },
        ));
    }


    public function getName()
    {
        return 'visit_list';
    }

}
