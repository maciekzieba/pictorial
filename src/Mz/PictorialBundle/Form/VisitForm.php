<?php

namespace Mz\PictorialBundle\Form;

use Doctrine\ORM\EntityRepository;
use Mz\PictorialBundle\Entity\Package;
use Mz\PictorialBundle\Entity\Visit;
use Mz\PictorialBundle\Form\Type\DateMonthType;
use Mz\PictorialBundle\Service\PackageService;
use Mz\PictorialBundle\Service\VisitService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class VisitForm extends AbstractType
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

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', 'text', array(
                'label' => 'Numer',
                'constraints' => array(
                    new Assert\NotBlank()
                )
            ))
            ->add('visitDate', 'date', array(
                'label' => 'Data wizyty',
                'attr' => array('class' => 'date'),
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'constraints' => array(

                )
            ))
            ->add('firstname', 'text', array(
                'label' => 'Imię',
                'constraints' => array(
                )
            ))
            ->add('lastname', 'text', array(
                'label' => 'Nazwisko',
                'constraints' => array(
                )
            ))
            ->add('city', 'text', array(
                'label' => 'Miasto',
                'constraints' => array(
                )
            ))
            ->add('district', 'text', array(
                'label' => 'Dzielnica',
                'constraints' => array(
                )
            ))
            ->add('cardNumber', 'text', array(
                'label' => 'Numer karty IKEA FAMILY',
                'constraints' => array(
                )
            ))
            ->add('lpId', 'text', array(
                'label' => 'LP ID',
                'constraints' => array(
                )
            ))
            ->add('contactSource', 'choice', array(
                'label' => 'Skąd kontakt',
                'choices' => $this->visitService->getContactSources(),
                'constraints' => array(
                )
            ))
            ->add('realizationStatus', 'choice', array(
                'label' => 'Status realizacji',
                'choices' => $this->visitService->getRealizationStatuses(),
                'constraints' => array(
                )
            ))
            ->add('paymentStatus', 'choice', array(
                'label' => 'Status płatności',
                'choices' => $this->visitService->getPaymentStatuses(),
                'constraints' => array(
                )
            ))
            ->add('description', 'textarea', array(
                'label' => 'Opis',
                'attr' => array('rows' => '8'),
                'constraints' => array(
                )
            ))
            ->add('restrictions', 'textarea', array(
                'label' => 'Restrykcje',
                'attr' => array('rows' => '8'),
                'constraints' => array(
                )
            ))
            ->add('newsletter', 'checkbox', array(
                'label' => 'Publikacja w biuletynie?',
                'constraints' => array(
                )
            ))
            ->add('rightsDeadline', 'date', array(
                'label' => 'Data praw do zdjęć',
                'attr' => array('class' => 'date'),
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'constraints' => array(
                )
            ))
            ->add('externalsCosts', 'money', array(
                'label' => 'Koszty zewnętrzne',
                'currency' => 'PLN',
                'constraints' => array(
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
                'constraints' => array(
                    new Assert\NotBlank()
                )
            ))
            ->add('categories', 'entity', array(
                'label' => 'Kategorie',
                'class' => 'MzPictorialBundle:Category',
                'property' => 'name',
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name')
                        ;
                },
                'constraints' => array(

                )
            ))
        ;

        $packageEvent = function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            if ($data instanceof Visit && $data->getId() && $data->getPackage() instanceof Package) {
                $queryBuilder = function (EntityRepository $er) use ($data) {
                    return $er->createQueryBuilder('p')
                        ->leftJoin('p.visits', 'v')
                        ->groupBy('p.id')
                        ->having('(p.visitsQuantity - COUNT(v.id)) > 0 OR (p.id = :packageId) ')
                        ->setParameter('packageId', $data->getPackage()->getId())
                        ;
                };
            } else {
                $queryBuilder = function (EntityRepository $er) use ($data) {
                    return $er->createQueryBuilder('p')
                        ->leftJoin('p.visits', 'v')
                        ->groupBy('p.id')
                        ->having('(p.visitsQuantity - COUNT(v.id)) > 0')
                        ;
                };
            }

            $form->add('package', 'entity', array(
                'label' => 'Pakiet',
                'class' => 'MzPictorialBundle:Package',
                'property' => 'fullName',
                'query_builder' => $queryBuilder,
                'constraints' => array(
                    new Assert\NotBlank()
                )
            ));


        };
        $builder->addEventListener(FormEvents::PRE_SET_DATA, $packageEvent);
    }


    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mz\PictorialBundle\Entity\Visit'
        ));
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'visit';
    }

}
