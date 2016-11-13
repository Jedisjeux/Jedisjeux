<?php

/*
 * This file is part of Jedisjeux project.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Factory;
use AppBundle\Document\StringBlock;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class StringBlockFactory
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @var ObjectManager
     */
    protected $documentManager;

    /**
     * @var string
     */
    protected $stringBlockParentPath;

    /**
     * StringBlockFactory constructor.
     *
     * @param string $className
     * @param ObjectManager $documentManager
     * @param string $stringBlockParentPath
     */
    public function __construct($className, ObjectManager $documentManager, $stringBlockParentPath)
    {
        $this->className = $className;
        $this->documentManager = $documentManager;
        $this->stringBlockParentPath = $stringBlockParentPath;
    }

    /**
     * @return StringBlock
     */
    public function createNew()
    {
        /** @var StringBlock $string_block */
        $string_block = new $this->className();
        $string_block->setParentDocument($this->documentManager->find(null, $this->stringBlockParentPath));

        return $string_block;
    }
}
