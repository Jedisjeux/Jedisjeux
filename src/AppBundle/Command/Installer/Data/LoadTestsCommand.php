<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Jedisjeux
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Document\ArticleContent;
use AppBundle\Document\BlockquoteBlock;
use Doctrine\ODM\PHPCR\Document\Generic;
use Doctrine\ODM\PHPCR\DocumentRepository;
use PHPCR\Util\NodeHelper;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ImagineBlock;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Image;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadTestsCommand extends AbstractLoadDocumentCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:tests:load')
            ->setDescription('Load tests');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        foreach ($this->getTests() as $data) {
            $output->writeln(sprintf("Loading test of <info>%s</info> product", $data['product_name']));

            $page = $this->createOrReplaceTest($data);
            $block = $this->createOrReplaceIntroductionBlock($page, $data);
            $page->addChild($block);

            $this->getDocumentManager()->persist($page);
            $this->getDocumentManager()->flush();
        }
    }

    /**
     * @param array $data
     * @return ArticleContent
     */
    protected function createOrReplaceTest(array $data)
    {
        $articleDocument = $this->findPage($data['name']);

        if (null === $articleDocument) {
            $articleDocument = new ArticleContent();
            $articleDocument
                ->setParentDocument($this->getParent());
        }

        $articleDocument->setName($data['name']);
        $articleDocument->setTitle($data['title']);
        $articleDocument->setPublishable(true);
        $articleDocument->setPublishStartDate(\DateTime::createFromFormat('Y-m-d H:i:s', $data['publishedAt']));

        return $articleDocument;
    }

    protected function createOrReplaceIntroductionBlock(ArticleContent $page, array $data)
    {
        $name = 'block0';

        $block = $this
            ->getSingleImageBlockRepository()
            ->findOneBy(array('name' => $name));

        if (null === $block) {
            $block = new BlockquoteBlock();
            $block
                ->setParentDocument($page);
        }

        $block
            ->setBody(sprintf('<p>%s</p>', $data['introduction']))
            ->setName($name)
            ->setPublishable(true);

        return $block;
    }

    /**
     * @param string $name
     * @return ArticleContent
     */
    protected function findPage($name)
    {
        $page = $this
            ->getRepository()
            ->findOneBy(array('name' => $name));

        return $page;
    }

    /**
     * @return Generic
     */
    protected function getParent()
    {
        $contentBasepath = '/cms/pages/articles/tests';
        $parent = $this->getDocumentManager()->find(null, $contentBasepath);

        if (null === $parent) {
            $session = $this->getDocumentManager()->getPhpcrSession();
            NodeHelper::createPath($session, $contentBasepath);
            $parent = $this->getDocumentManager()->find(null, $contentBasepath);
        }

        return $parent;
    }

    /**
     * @return DocumentRepository
     */
    public function getRepository()
    {
        return $this->getContainer()->get('app.repository.article_content');
    }

    /**
     * @return array
     */
    protected function getTests()
    {
        $query = <<<EOM
select test.game_id as id,
       test.date as publishedAt,
       product.id as product_id,
       productTranslation.name as product_name,
       concat('Test de ', productTranslation.name) as name,
       topic.id as topic_id,
       customer.id as author_id,
       test.intro as introduction,
       test.materiel as material,
       test.regle as rules
from jedisjeux.jdj_tests test
  inner join sylius_product_variant productVariant
    on productVariant.code = concat('game-', test.game_id)
  inner join sylius_product product
    on product.id = productVariant.product_id
  inner join sylius_product_translation productTranslation
    on productTranslation.translatable_id = product.id
       and locale = 'fr'
  left join jdj_topic topic
    on topic.id = test.topic_id
  inner join sylius_customer customer
    on customer.code = concat('user-', test.user_id)
limit 5;
EOM;

        return $this->getDatabaseConnection()->fetchAll($query);
    }
}
