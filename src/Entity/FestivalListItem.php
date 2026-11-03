<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
#[ORM\Entity]
#[UniqueEntity(fields: ['list', 'product'], errorPath: 'product', message: 'app.festival_list_item.product.unique')]
#[ORM\Table('jdj_festival_list_item')]
class FestivalListItem implements ResourceInterface
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @var FestivalList
     */
    #[ORM\ManyToOne(targetEntity: \FestivalList::class)]
    protected $list;

    /**
     * @var ProductInterface
     */
    #[ORM\ManyToOne(targetEntity: \Sylius\Component\Product\Model\ProductInterface::class)]
    protected $product;

    public function getList(): ?FestivalList
    {
        return $this->list;
    }

    public function setList(?FestivalList $list): void
    {
        $this->list = $list;
    }

    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    public function setProduct(?ProductInterface $product): void
    {
        $this->product = $product;
    }
}
