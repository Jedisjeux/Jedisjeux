<?php

/*
 * This file is part of the Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Class Avatar.
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_customer_avatar")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Avatar extends AbstractImage
{
    /**
     * The path to the  path files.
     */
    protected function getUploadDir(): string
    {
        return 'uploads/avatar';
    }
}
