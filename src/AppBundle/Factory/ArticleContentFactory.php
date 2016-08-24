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

use AppBundle\Document\ArticleContent;
use Doctrine\ODM\PHPCR\Document\Generic;
use Doctrine\ODM\PHPCR\DocumentManager;
use PHPCR\Util\NodeHelper;
use Sylius\Component\Resource\Factory\Factory;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ArticleContentFactory extends Factory
{
    /**
     * @var DocumentManager
     */
    protected $documentManager;

    /**
     * @return ArticleContent
     */
    public function createNew()
    {
        /** @var ArticleContent $articleContent */
        $articleContent = parent::createNew();


        $articleContent
            ->setParentDocument($this->getParent());

        return $articleContent;
    }

    /**
     * @param DocumentManager $documentManager
     */
    public function setDocumentManager($documentManager)
    {
        $this->documentManager = $documentManager;
    }

    /**
     * @return Generic
     */
    protected function getParent()
    {
        $contentBasepath = '/cms/pages/articles';
        /** @var Generic $parent */
        $parent = $this->documentManager->find(null, $contentBasepath);

        if (null === $parent) {
            $session = $this->documentManager->getPhpcrSession();
            NodeHelper::createPath($session, $contentBasepath);
            $parent = $this->documentManager->find(null, $contentBasepath);
        }

        return $parent;
    }
}
