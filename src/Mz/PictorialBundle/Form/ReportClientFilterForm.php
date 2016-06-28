<?php

namespace Mz\PictorialBundle\Form;

use Doctrine\ORM\EntityRepository;
use Mz\PictorialBundle\Entity\User;
use Mz\PictorialBundle\Form\Type\DateMonthType;
use Mz\PictorialBundle\Service\PackageService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class ReportClientFilterForm extends AbstractType
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

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('package', 'entity', array(
                'label' => 'Pakiet',
                'class' => 'MzPictorialBundle:Package',
                'property' => 'fullName',
                'placeholder' => '',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.validityDate', 'DESC')
                        ;
                },
            ))
            ->add('packageDateFrom', new DateMonthType(), array(
                'label' => 'Data pakietu od',
                'attr' => array('class' => 'hasDate hasDatePicker'),
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy'
            ))
            ->add('packageDateTo', new DateMonthType(), array(
                'label' => 'Data pakietu do',
                'attr' => array('class' => 'hasDate hasDatePicker'),
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy'
            ))
        ;

    }


    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mz\PictorialBundle\Model\ReportClientFilter'
        ));
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'report_client_filter';
    }

}
