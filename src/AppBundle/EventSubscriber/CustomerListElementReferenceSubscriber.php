<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 12/04/2016
 * Time: 13:35
 */

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\CustomerListElement;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Sylius\Component\Resource\Model\ResourceInterface;


/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class CustomerListElementReferenceSubscriber implements EventSubscriber
{
    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::preUpdate,
            Events::postLoad,
        );
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $customerListElement = $event->getObject();

        if (!$customerListElement instanceof CustomerListElement) {
            return;
        }

        $element = $customerListElement->getElement();

        if (null === $element) {
            return;
        }

        $customerListElement
            ->setObjectClass(get_class($element))
            ->setObjectId($element->getId());
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(LifecycleEventArgs $event)
    {
        $this->prePersist($event);
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postLoad(LifecycleEventArgs $event)
    {
        $customerListElement = $event->getObject();

        if (!$customerListElement instanceof CustomerListElement) {
            return;
        }

        if (null === $customerListElement->getObjectId()) {
            return;
        }

        if (null !== $customerListElement->getProduct()) {
            return;
        }

        /** @var EntityRepository $repository */
        $repository = $event->getEntityManager()->getRepository($customerListElement->getObjectClass());

        /** @var ResourceInterface $element */
        $element = $repository->find($customerListElement->getObjectId());

        $customerListElement->setElement($element);
    }
}