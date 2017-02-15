<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;
use Zenstruck\RedirectBundle\Model\Redirect as BaseRedirect;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_redirect")
 */
class Redirect extends BaseRedirect implements ResourceInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @param string $source
     * @param string $destination
     * @param bool   $permanent
     */
    public function __construct($source = null, $destination = null, $permanent = true)
    {
        parent::__construct($source, $destination, $permanent);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
