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


class UserEditForm extends AbstractType
{
    protected $roles = array();

    /**
     * @param array $roles
     */
    public function __construct($roles = array())
    {
        $this->roles = $roles;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Podane hasła nie są identyczne.',
                'first_options' => array('label' => 'Hasło'),
                'second_options' => array('label' => 'Powtórz hasło')
            ))
            ->add('firstname', 'text', array(
                'label' => 'Imię',
                'constraints' => array(
                )
            ))
            ->add('lastname', 'text', array(
                'label' => 'Nazwisko',
                'constraints' => array(
                    new Assert\NotBlank(),
                )
            ))
            ->add('roles', 'choice', array(
                'multiple' => TRUE,
                'expanded' => FALSE,
                'choices' => $this->roles,
                'label' => 'Role',
                'required' => true,
                'constraints' => array(
                    new Assert\NotBlank(),
                )
            ))
            ->add('locked', 'checkbox', array(
                'label' => 'Zablokuj',
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
            'data_class' => 'Mz\PictorialBundle\Entity\User'
        ));
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'user';
    }

}
