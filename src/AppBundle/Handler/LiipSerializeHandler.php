<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Handler;

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
     *
     * @param CacheManager $manager
     */
    function __construct(CacheManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return array
     */
    public static function getSubscribingMethods()
    {
        return array(
            array(
                'direction' => GraphNavigator::DIRECTION_SERIALIZATION,
                'format' => 'json',
                'type' => 'LiipSerializer',
                'method' => 'serializeLiipSerializerTojson',
            ),
        );
    }

    /**
     * @param VisitorInterface $visitor
     * @param array $imageData
     * @param array $type
     *
     * @return string
     */
    public function serializeLiipSerializerTojson(VisitorInterface $visitor, array $imageData, array $type)
    {
        return $this->manager->getBrowserPath($imageData['filename'], $imageData['filter']);
    }
}
