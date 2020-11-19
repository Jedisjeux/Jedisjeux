<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Fixture;

use App\Fixture\Factory\PubBannerExampleFactory;
use Doctrine\Common\Persistence\ObjectManager;

final class PubBannerFixture extends AbstractResourceFixture
{
    public function __construct(ObjectManager $objectManager, PubBannerExampleFactory $exampleFactory)
    {
        parent::__construct($objectManager, $exampleFactory);
    }

    public function getName(): string
    {
        return 'pub_banner';
    }
}
