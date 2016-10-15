<?php

namespace Mz\PictorialBundle\Form\Type;

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


class VisitCostType extends AbstractType
{


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('visitRole', 'entity', array(
                'label' => 'Rola',
                'class' => 'MzPictorialBundle:VisitRole',
                'property' => 'name',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r');
                },
                'constraints' => array(
                    new Assert\NotBlank()
                )
            ))
            ->add('price', 'money', array(
                'label' => 'Cena',
                'currency' => 'PLN',
                'constraints' => array(
                    new Assert\NotBlank(),
                )
            ))
            ->add('user', 'entity', array(
                'label' => 'Osoba',
                'class' => 'MzPictorialBundle:User',
                'property' => 'fullName',
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
        ;


    }


    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mz\PictorialBundle\Entity\VisitCost'
        ));
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'visit_cost_type';
    }

}
