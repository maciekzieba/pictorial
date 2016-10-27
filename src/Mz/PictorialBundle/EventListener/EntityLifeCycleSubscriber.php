<?php


namespace Mz\PictorialBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;


class EntityLifeCycleSubscriber implements EventSubscriber
{
    /**
     * @var ContainerInterface
     */
    private $container;


    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'preFlush',
            'onFlush'
        );
    }


    /**
     * @param PreFlushEventArgs $eventArgs
     */
    public function preFlush(PreFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();
        $user = $this->getCurrentUser();

        // Insertions:
        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if (method_exists($entity, 'setCreatedAt')) {
                $entity->setCreatedAt(new \DateTime());
            }

            if (method_exists($entity, 'setCreatedBy')) {
                $entity->setCreatedBy($user);
            }
        }

        /*
        foreach ($uow->getScheduledEntityUpdates() as $entity) {

        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {

        }

        foreach ($uow->getScheduledCollectionDeletions() as $col) {

        }

        foreach ($uow->getScheduledCollectionUpdates() as $col) {

        }
        */
    }


    /**
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();
        $user = $this->getCurrentUser();

        // Updates:
        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if (method_exists($entity, 'setUpdatedAt')) {
                $entity->setUpdatedAt(new \DateTime());
            }
            if (method_exists($entity, 'setUpdatedBy')) {
                $entity->setUpdatedBy($user);
            }
        }
    }


    /**
     * @return mixed|null
     */
    private function getCurrentUser()
    {
        static $user = false;
        if ($user === false) {
            $user = null;
            /** @var TokenStorageInterface $tokenStorage */
            $tokenStorage = $this->container->get('security.token_storage');
            $token = $tokenStorage->getToken();
            if ($token instanceof TokenInterface && is_object($token->getUser())) {
                $user = $token->getUser();
            }
        }

        return $user;
    }

}