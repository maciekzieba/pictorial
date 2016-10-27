<?php

namespace Mz\PictorialBundle\Service;

use Doctrine\ORM\EntityManager;
use Mz\PictorialBundle\Entity\Pricelist;
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
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function demandUser($id)
    {
        $user = $this->getUser($id);
        if (!$user instanceof User) {
            throw new \Exception('Nie odnaleziono użytkownika o id "' . $id . '"');
        }

        return $user;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getUser($id)
    {
        $builder = $this->em->createQueryBuilder();
        $builder->select('u')
            ->from('MzPictorialBundle:User', 'u')
            ->where('u.id = :id')
            ->setParameter('id', $id);

        return $builder->getQuery()->getOneOrNullResult();
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function demandPricelist($id)
    {
        $pricelist = $this->getPricelist($id);
        if (!$pricelist instanceof Pricelist) {
            throw new \Exception('Nie odnaleziono cennika o id "' . $id . '"');
        }

        return $pricelist;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getPricelist($id)
    {
        $builder = $this->em->createQueryBuilder();
        $builder->select('p')
            ->from('MzPictorialBundle:Pricelist', 'p')
            ->where('p.id = :id')
            ->setParameter('id', $id);

        return $builder->getQuery()->getOneOrNullResult();
    }

    /**
     * @param Pricelist $pricelist
     */
    public function savePricelist(Pricelist $pricelist)
    {
        $this->em->persist($pricelist);
        $this->em->flush();
    }

    /**
     * @param Pricelist $pricelist
     */
    public function removePricelist(Pricelist $pricelist)
    {
        $this->em->remove($pricelist);
        $this->em->flush();
    }

    public function getAllPricelistArray()
    {
        $builder = $this->em->createQueryBuilder();
        $builder->select('p, u, v')
            ->from('MzPictorialBundle:Pricelist', 'p')
            ->innerJoin('p.user', 'u')
            ->innerJoin('p.visitRole', 'v');

        $result = $builder->getQuery()->getArrayResult();

        $pricelist = array();

        foreach ($result as $row) {
            if (!isset($pricelist[$row['user']['id']])) {
                $pricelist[$row['user']['id']] = array();
            }
            $pricelist[$row['user']['id']][$row['visitRole']['id']] = $row['price'];

        }

        return $pricelist;


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