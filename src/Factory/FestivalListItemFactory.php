<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Factory;

use App\Entity\FestivalList;
use App\Entity\FestivalListItem;
use Sylius\Component\Resource\Factory\FactoryInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class FestivalListItemFactory implements FactoryInterface
{
    /**
     * @var string
     */
    private $className;

    /**
     * @param string $className
     */
    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * @return FestivalListItem
     */
    public function createNew()
    {
        return new $this->className();
    }

    /**
     * @return FestivalListItem
     */
    public function createForList(FestivalList $list)
    {
        $festivalListItem = $this->createNew();

        $festivalListItem
            ->setList($list);

        return $festivalListItem;
    }
}
