<?php

namespace Mz\PictorialBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PackageVisits extends Constraint
{
    public $message = 'Nie można ustawić takiej ilości wizyt. Minimalna wartośc to: %value%';

    public function validatedBy()
    {
        return 'packageVisits.validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}