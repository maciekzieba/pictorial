<?php

namespace Mz\PictorialBundle\Service;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Mz\PictorialBundle\Entity\Category;
use Mz\PictorialBundle\Entity\Visit;


/**
 * @Service("pictorial.category")
 */
class CategoryService
{
    /**
     * @var EntityManager
     */
    private $em;


    /**
     * @InjectParams({
     *     "em"                     = @Inject("doctrine.orm.entity_manager")
     * })
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function demandCategory($id)
    {
        $category = $this->getCategory($id);
        if (!$category instanceof Category) {
            throw new \Exception('Nie odnaleziono kategorii o id "' . $id . '"');
        }

        return $category;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCategory($id)
    {
        $builder = $this->em->createQueryBuilder();
        $builder->select('c')
            ->from('MzPictorialBundle:Category', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $id);

        return $builder->getQuery()->getOneOrNullResult();
    }


    /**
     * @param Category $category
     * @return Category
     */
    public function saveCategory(Category $category)
    {

        $this->em->persist($category);
        $this->em->flush();

        return $category;
    }

    /**
     * @param Category $category
     */
    public function removeCategory(Category $category)
    {
        $this->em->remove($category);
        $this->em->flush();
    }
}