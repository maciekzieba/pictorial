<?php

namespace Mz\PictorialBundle\Service;

use Doctrine\ORM\EntityManager;
use Mz\PictorialBundle\Entity\Package;
use Mz\PictorialBundle\Entity\Publication;
use Mz\PictorialBundle\Entity\User;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use JMS\DiExtraBundle\Annotation\Service;
use Mz\PictorialBundle\Entity\Visit;


/**
 * @Service("pictorial.publication")
 */
class PublicationService
{
    /**
     * @var EntityManager
     */
    private $em;

    protected $types = array(
        'facebook' => 'Facebook',
        'ikeafamily' => 'IKEA FAMILY',
        'pixieset' => 'Pixieset',
        'other' => 'Inne',
    );


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
     * @param $type
     * @return string
     */
    public function getTypeText($type)
    {
        if (isset($this->types[$type])) {
            return $this->types[$type];
        }
        return "";
    }

    /**
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function demandPublication($id)
    {
        $publication = $this->getPublication($id);
        if (!$publication instanceof Publication) {
            throw new \Exception('Nie odnaleziono publikacji o id "' . $id . '"');
        }

        return $publication;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getPublication($id)
    {
        $builder = $this->em->createQueryBuilder();
        $builder->select('p')
            ->from('MzPictorialBundle:Publication', 'p')
            ->where('p.id = :id')
            ->setParameter('id', $id);

        return $builder->getQuery()->getOneOrNullResult();
    }


    /**
     * @param Publication $publication
     * @return Publication
     */
    public function savePublication(Publication $publication)
    {

        $this->em->persist($publication);
        $this->em->flush();

        return $publication;
    }

    /**
     * @param Publication $publication
     */
    public function removePublication(Publication $publication)
    {
        $this->em->remove($publication);
        $this->em->flush();
    }
}