<?php

namespace Mz\PictorialBundle\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use Mz\PictorialBundle\Entity\Package;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class PackageVisitsValidator extends ConstraintValidator
{

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function validate($package, Constraint $constraint)
    {
        if ($package instanceof Package) {
            if (count($package->getVisits()) > $package->getVisitsQuantity()) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('%value%', count($package->getVisits()))
                    ->addViolation();
            }
        }
    }

}