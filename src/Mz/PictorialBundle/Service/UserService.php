<?php

namespace Mz\PictorialBundle\Service;

use Doctrine\ORM\EntityManager;
use Mz\PictorialBundle\Entity\User;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;


/**
 * @Service("pictorial.user")
 */
class UserService
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var array
     */
    protected $rolesHierarchy;

    /**
     * @InjectParams({
     *     "em"                     = @Inject("doctrine.orm.entity_manager"),
     *     "rolesHierarchy"         = @Inject("%security.role_hierarchy.roles%")
     * })
     */
    public function __construct(EntityManager $em, $rolesHierarchy)
    {
        $this->em = $em;
        $this->rolesHierarchy = $rolesHierarchy;
    }


    /**
     * @return mixed
     */
    public function getRolesHierarchy()
    {
        return $this->rolesHierarchy;
    }


    /**
     * @return array
     */
    public function getRolesList()
    {
        $roles = array();
        foreach (array_keys($this->rolesHierarchy) as $role) {
            $roles[ $role ] = $role;
        }

        return $roles;
    }

}