<?php

namespace Mz\PictorialBundle\Form;

use Doctrine\ORM\EntityRepository;
use Mz\PictorialBundle\Entity\User;
use Mz\PictorialBundle\Service\PublicationService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class UserPricelistForm extends AbstractType
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
        ;

    }


    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mz\PictorialBundle\Entity\Pricelist'
        ));
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'pricelist';
    }

}
