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


class PackageForm extends AbstractType
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
            ->add('validityDate', new DateMonthType(), array(
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'html5' => false,
                'attr' => array('class' => 'hasDate hasDatePicker'),
                'label' => 'Miesiąc obowiązywania',
                'constraints' => array(
                    new Assert\NotBlank(),
                )
            ))
            //TODO: WAZNE WALIDACJA PRZY EDYCJI JAK SA WIZYTY JUZ
            ->add('visitsQuantity', 'integer', array(
                'label' => 'Liczba wizyt',
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\GreaterThan(array('value' => 0))
                )
            ))
            ->add('priceNet', 'money', array(
                'label' => 'Cena pakietu netto',
                'constraints' => array(
                    new Assert\NotBlank()
                )
            ))
            ->add('status', 'choice', array(
                'label' => 'Status',
                'choices' => $this->packageService->getStatuses(),
                'constraints' => array(
                    new Assert\NotBlank()
                )
            ))
            ->add('invoiceNumber', 'text', array(
                'label' => 'Numer faktury',
                'constraints' => array(

                )
            ))
            ->add('invoiceValueNet', 'money', array(
                'label' => 'Kwota netto faktury',
                'constraints' => array(

                )
            ))
            ->add('description', 'textarea', array(
                'label' => 'Opis',
                'attr' => array('rows' => '15'),
                'constraints' => array(

                )
            ))

        ;

    }


    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mz\PictorialBundle\Entity\Package'
        ));
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'package';
    }

}
