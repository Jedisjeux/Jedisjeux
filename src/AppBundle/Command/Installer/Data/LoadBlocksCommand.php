<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 19/01/2016
 * Time: 13:53
 */

namespace AppBundle\Command\Installer\Data;

use Doctrine\ODM\PHPCR\Document\Generic;
use Doctrine\ODM\PHPCR\DocumentManager;
use PHPCR\Util\NodeHelper;
use Sylius\Bundle\ResourceBundle\Doctrine\ODM\PHPCR\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SimpleBlock;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadBlocksCommand extends ContainerAwareCommand
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:blocks:load')
            ->setDescription('Load blocks');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        foreach ($this->getBlocks() as $data) {
            $block = $this->createOrReplaceBlock($data);
            $this->getManager()->persist($block);
            $this->getManager()->flush();
        }
    }

    /**
     * @param array $data
     * @return SimpleBlock
     */
    protected function createOrReplaceBlock(array $data)
    {
        $block = $this->findBlock($data['name']);

        if (null === $block) {
            $block = new SimpleBlock();
            $block
                ->setParentDocument($this->getParent());
        }

        $block->setName($data['name']);
        $block->setTitle($data['title']);
        $block->setBody($data['body']);
        $block->setPublishable(true);

        return $block;
    }

    /**
     * @param string $id
     * @return SimpleBlock
     */
    protected function findBlock($id)
    {
        $page = $this
            ->getRepository()
            ->find('/cms/blocks/' . $id);

        return $page;
    }

    /**
     * @return Generic
     */
    protected function getParent()
    {
        $contentBasepath = '/cms/blocks';
        $parent = $this->getManager()->find(null, $contentBasepath);

        if (null === $parent) {
            $session = $this->getManager()->getPhpcrSession();
            NodeHelper::createPath($session, $contentBasepath);
            $parent = $this->getManager()->find(null, $contentBasepath);
        }

        return $parent;
    }

    /**
     * @return DocumentRepository
     */
    public function getRepository()
    {
        return $this->getContainer()->get('sylius.repository.simple_block');
    }

    /**
     * @return DocumentManager
     */
    public function getManager()
    {
        return $this->getContainer()->get('sylius.manager.static_content');
    }

    protected function getBlocks()
    {
        return array(
            array(
                'name' => 'about',
                'title' => 'A propos',
                'body' => '
<p>Jedisjeux est une association loi 1901.</p>
                ',
            ),
            array(
                'name' => 'head-office',
                'title' => 'Siège social',
                'body' => '
    <p class="add">
        16 rue DOM François Plaine<br>
        35137 Bédée</p>
    ',
            ),
        );
    }
}