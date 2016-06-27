<?php

namespace Mz\PictorialBundle\Form;

use Doctrine\ORM\EntityRepository;
use Mz\PictorialBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class PublicationForm extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url', 'url', array(
                'label' => 'URL',
                'constraints' => array(
                    new Assert\NotBlank(),
                )
            ))
            ->add('type', 'choice', array(
                'choices' => array(
                    'Facebook' => 'Facebook',
                    'IKEA FAMILY' => 'IKEA FAMILY',
                    'Pixieset' => 'Pixieset',
                    'Other' => 'Other'
                ),
                'label' => 'Typ',
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
            'data_class' => 'Mz\PictorialBundle\Entity\Publication'
        ));
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'publication';
    }

}
