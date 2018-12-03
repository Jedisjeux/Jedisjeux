<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use App\Entity\ProductBox;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;

class CreateProductBoxThumbnailSubscriber implements EventSubscriber
{
    const FILTER = 'product_box';

    /**
     * @var DataManager
     */
    private $dataManager;

    /**
     * @var FilterManager
     */
    private $filterManager;

    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @param DataManager $dataManager
     * @param FilterManager $filterManager
     * @param CacheManager $cacheManager
     */
    public function __construct(DataManager $dataManager, FilterManager $filterManager, CacheManager $cacheManager)
    {
        $this->dataManager = $dataManager;
        $this->filterManager = $filterManager;
        $this->cacheManager = $cacheManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist
        ];
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        /** @var ProductBox $box */
        $box = $args->getObject();

        if (false === $box instanceof ProductBox) {
            return;
        }

        $this->createThumbnail($box);
    }

    /**
     * @param ProductBox $box
     */
    public function createThumbnail(ProductBox $box)
    {
        $imagePath = $box->getImage()->getWebPath();

        $binary = $this->dataManager->find(static::FILTER, $imagePath);
        $filteredBinary = $this->filterManager->applyFilter($binary, static::FILTER, [
            'filters' => [
                'relative_resize' => [
                    'heighten' => $box->getHeight(),
                ]
            ]
        ]);

        $this->cacheManager->store($filteredBinary, $imagePath, static::FILTER);
    }
}
