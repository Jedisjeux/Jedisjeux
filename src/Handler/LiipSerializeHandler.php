<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Handler;

use JMS\Serializer\GraphNavigator;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\VisitorInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LiipSerializeHandler implements SubscribingHandlerInterface
{
    /**
     * @var CacheManager
     */
    protected $manager;

    /**
     * LiipSerializeHandler constructor.
     */
    public function __construct(CacheManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return array
     */
    public static function getSubscribingMethods()
    {
        return [
            [
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'LiipSerializer',
                'method' => 'serializeLiipSerializerTojson',
            ],
        ];
    }

    /**
     * @return string
     */
    public function serializeLiipSerializerTojson(VisitorInterface $visitor, array $imageData, array $type)
    {
        return $this->manager->getBrowserPath($imageData['filename'], $imageData['filter']);
    }
}
