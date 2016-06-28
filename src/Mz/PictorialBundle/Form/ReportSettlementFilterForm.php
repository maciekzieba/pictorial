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


class ReportSettlementFilterForm extends AbstractType
{
    /** @var  User */
    protected $user;


    /**
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('dateFrom', 'date', array(
                'label' => 'Data od',
                'attr' => array('class' => 'date'),
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy'
            ))
            ->add('dateTo', 'date', array(
                'label' => 'Data  do',
                'attr' => array('class' => 'date'),
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy'
            ))
            ->add('user', 'entity', array(
                'label' => 'Osoba',
                'class' => 'MzPictorialBundle:User',
                'property' => 'fullName',
                'query_builder' => function (EntityRepository $er) {
                    if ($this->user->hasRole('ROLE_SUPER_ADMIN')) {
                        return $er->createQueryBuilder('u')
                            ->where('u.roles LIKE :roles')
                            ->setParameter('roles', '%ADMIN%')
                            ->orderBy('u.id');
                    }
                    return $er->createQueryBuilder('u')
                        ->where('u.id LIKE :id')
                        ->setParameter('id', $this->user->getId())
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
            'data_class' => 'Mz\PictorialBundle\Model\ReportSettlementFilter'
        ));
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'report_settlement_filter';
    }

}
