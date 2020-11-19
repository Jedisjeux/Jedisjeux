<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\PublicationManager;

use App\Entity\ProductBox;
use Doctrine\Common\Persistence\ObjectManager;
use Webmozart\Assert\Assert;

class ProductBoxPublicationManager
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function enable(ProductBox $box): void
    {
        $variant = $box->getProductVariant();
        Assert::notNull($variant);

        $previousBox = $variant->getEnabledBox();

        if (null !== $previousBox) {
            $previousBox->disable();
        }

        $box->enable();

        $this->objectManager->flush();
    }
}
