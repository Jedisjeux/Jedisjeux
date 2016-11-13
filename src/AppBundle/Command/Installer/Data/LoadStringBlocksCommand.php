<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 19/01/2016
 * Time: 13:53
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Document\StringBlock;
use AppBundle\Factory\StringBlockFactory;
use Doctrine\ODM\PHPCR\DocumentManager;
use Sylius\Bundle\ResourceBundle\Doctrine\ODM\PHPCR\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SimpleBlock;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadStringBlocksCommand extends ContainerAwareCommand
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
            ->setName('app:string-blocks:load')
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
     * @return StringBlock
     */
    protected function createOrReplaceBlock(array $data)
    {
        $block = $this->findBlock($data['name']);

        if (null === $block) {
            $block = $this->getFactory()->createNew();
        }

        $block->setName($data['name']);
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
            ->find('/cms/content/' . $id);

        return $page;
    }

    /**
     * @return StringBlockFactory
     */
    public function getFactory()
    {
        return $this->getContainer()->get('app.factory.string_block');
    }

    /**
     * @return DocumentRepository
     */
    public function getRepository()
    {
        return $this->getContainer()->get('app.repository.string_block');
    }

    /**
     * @return DocumentManager
     */
    public function getManager()
    {
        return $this->getContainer()->get('app.manager.string_block');
    }

    protected function getBlocks()
    {
        return array(
            array(
                'name' => 'about',
                'body' => '
<p>Jedisjeux est une association loi 1901.</p>
                ',
            ),
            array(
                'name' => 'head-office',
                'body' => '
    <p class="add">
        16 rue DOM François Plaine<br>
        35137 Bédée</p>
    ',
            ),
        );
    }
}