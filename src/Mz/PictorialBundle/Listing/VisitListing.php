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
            ->add('createdAt', 'column', array(
                'label' => 'Utworzony',
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
                $builder->select('v')
                    ->from('MzPictorialBundle:Visit', 'v');

            },
        ));
    }


    public function getName()
    {
        return 'visit_list';
    }

}
