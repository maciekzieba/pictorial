<?php

namespace Mz\PictorialBundle\Form\Type;

use Mz\PictorialBundle\DataTransformer\DateMonthToRegularDateTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ToggleType
 * @package Mz\PictorialBundle\Form\Type
 *
 * Wtyczka: http://www.bootstraptoggle.com/
 */
class DateMonthType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addViewTransformer(new DateMonthToRegularDateTransformer());
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return 'date';
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'date_month';
    }
}