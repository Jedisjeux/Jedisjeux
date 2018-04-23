<?php

namespace AppBundle\Command\Installer\Data;

use AppBundle\Entity\AbstractImage;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DownloadImageCommand extends ContainerAwareCommand
{
    const DEFAULT_IMAGE_ORIGINAL_PATH = 'https://www.jedisjeux.net/media/cache/resolve/full/uploads/img/';

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * {@inheritdoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->input = $input;
        $this->output = $output;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:images:download')
            ->setDescription('Download images')
            ->addOption('image-original-path', null, InputOption::VALUE_REQUIRED, null, self::DEFAULT_IMAGE_ORIGINAL_PATH);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityRepository $repository */
        $repository = $this->getContainer()->get('app.repository.product_variant_image');
        $queryBuilder = $repository->createQueryBuilder('o');
        $this->downloadImages($queryBuilder);

        /** @var EntityRepository $repository */
        $repository = $this->getContainer()->get('app.repository.game_play_image');
        $queryBuilder = $repository->createQueryBuilder('o');
        $this->downloadImages($queryBuilder);

        /** @var EntityRepository $repository */
        $repository = $this->getContainer()->get('app.repository.person_image');
        $queryBuilder = $repository->createQueryBuilder('o');
        $this->downloadImages($queryBuilder);

        /** @var EntityRepository $repository */
        $repository = $this->getContainer()->get('app.repository.article_image');
        $queryBuilder = $repository->createQueryBuilder('o');
        $this->downloadImages($queryBuilder);

        /** @var EntityRepository $repository */
        $repository = $this->getContainer()->get('app.repository.block_image');
        $queryBuilder = $repository->createQueryBuilder('o');
        $this->downloadImages($queryBuilder);

        /** @var EntityRepository $repository */
        $repository = $this->getContainer()->get('app.repository.product_box_image');
        $queryBuilder = $repository->createQueryBuilder('o');
        $this->downloadImages($queryBuilder);
    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    protected function downloadImages(QueryBuilder $queryBuilder)
    {
        foreach ($queryBuilder->getQuery()->iterate() as $row) {
            /** @var AbstractImage $image */
            $image = $row[0];

            if (file_exists($image->getAbsolutePath())) {
                continue;
            }

            $this->downloadImage($image);


            $this->getManager()->detach($image);
            $this->getManager()->clear();
        }
    }

    /**
     * @return EntityManager|object
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    /**
     * @param AbstractImage $image
     *
     * @return string
     */
    protected function getImageOriginalPath(AbstractImage $image)
    {
        return $this->input->getOption('image-original-path') . $image->getPath();
    }

    /**
     * @param AbstractImage $image
     */
    protected function downloadImage(AbstractImage $image)
    {
        $copyFrom = $this->getImageOriginalPath($image);
        $copyTo = $image->getAbsolutePath();

        if (file_exists($copyTo)) {
            return;
        }

        $this->output->writeln(sprintf('Downloading image from <info>%s</info> to <info>%s</info>', $copyFrom, $copyTo));

        file_put_contents($copyTo, file_get_contents($this->getImageOriginalPath($image)));
    }
}
