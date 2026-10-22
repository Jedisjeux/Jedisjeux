<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;
use Zenstruck\RedirectBundle\Model\Redirect as BaseRedirect;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_redirect")
 */
class Redirection extends BaseRedirect implements ResourceInterface
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

    /**
     * {@inheritdoc}
     */
    public function setSource($source)
    {
        // Base model trims the source, which is deprecated with null since PHP 8.1
        parent::setSource((string) $source);
    }

    /**
     * {@inheritdoc}
     */
    public function setDestination($destination)
    {
        // Base model trims the destination, which is deprecated with null since PHP 8.1
        parent::setDestination((string) $destination);
    }
}
