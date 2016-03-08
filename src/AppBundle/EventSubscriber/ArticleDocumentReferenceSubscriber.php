<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 08/03/2016
 * Time: 11:36
 */

namespace AppBundle\EventSubscriber;

use AppBundle\Document\ArticleContent;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Article;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleDocumentReferenceSubscriber implements EventSubscriber
{
    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate',
            'postLoad',
        ];
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $article = $event->getEntity();

        if (!$article instanceof Article) {
            return;
        }

        $document = $article->getDocument();

        if (null === $document) {
            return;
        }

        $id = $document->getId();

        if (null === $id) {
            $manager = $this->managerRegistry->getManagerForClass(get_class($document));
            $manager->persist($document);
            $manager->flush();
            $id = $document->getId();
        }

        $article->setDocumentId($id);
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
        $article = $event->getObject();

        if (!$article instanceof Article) {
            return;
        }

        if (null === $article->getDocumentId()) {
            return;
        }

        /** @var ArticleContent $document */
        $document = $this->managerRegistry
            ->getManagerForClass(ArticleContent::class)
            ->find(null, $article->getDocumentId());

        $article->setDocument($document);
    }
}