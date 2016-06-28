<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 2014-12-15
 * Time: 10:39
 */

namespace Mz\PictorialBundle\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;


class DateMonthToRegularDateTransformer implements DataTransformerInterface
{
    private $separator = '/';

    /**
     * @param mixed $value
     * @return bool
     */
    public function transform($value)
    {
        $parts = explode($this->separator, $value);
        if (isset($parts[0])) {
            unset($parts[0]);
        }
        return implode($this->separator, $parts);
    }


    /**
     * @param mixed $value
     * @return mixed
     */
    public function reverseTransform($value)
    {
        $parts = explode($this->separator, $value);

        if (count($parts) < 3) {
            $newParts = array('01');
            $newParts = array_merge($newParts, $parts);
            return implode($this->separator, $newParts);
        }

        return implode($this->separator, $parts);
    }

}