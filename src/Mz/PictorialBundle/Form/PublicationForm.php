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


class PublicationForm extends AbstractType
{

    /** @var  PublicationService */
    protected $publicationService;

    /**
     * @param PublicationService $publicationService
     */
    public function __construct(PublicationService $publicationService)
    {
        $this->publicationService = $publicationService;
    }

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
                'choices' => $this->publicationService->getTypes(),
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
