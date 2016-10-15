<?php

namespace Mz\PictorialBundle\Form;

use Doctrine\ORM\EntityRepository;
use Mz\PictorialBundle\Entity\Package;
use Mz\PictorialBundle\Entity\Visit;
use Mz\PictorialBundle\Form\Type\DateMonthType;
use Mz\PictorialBundle\Form\Type\VisitCostType;
use Mz\PictorialBundle\Service\PackageService;
use Mz\PictorialBundle\Service\UserService;
use Mz\PictorialBundle\Service\VisitService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class VisitCostForm extends AbstractType
{

    /** @var UserService  */
    protected $userService;


    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('costs', 'collection', array(
                'entry_type'   => new VisitCostType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'by_reference' => false,
            ))
        ;


    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'pricelist'         => json_encode($this->userService->getAllPricelistArray()),

        ));
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
        return 'visit_cost';
    }

}
