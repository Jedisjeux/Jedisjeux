<?php

namespace App\Command\Installer\Data;

use App\Entity\AbstractImage;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DownloadImageCommand extends ContainerAwareCommand
{
    const DEFAULT_IMAGE_ORIGINAL_PATH = 'https://www.jedisjeux.net/uploads/img/';

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
            ->addOption('image-original-path', null, InputOption::VALUE_REQUIRED, 'Image source path', self::DEFAULT_IMAGE_ORIGINAL_PATH)
            ->addOption('entity', null, InputOption::VALUE_REQUIRED);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $repositories = $this->getRepositories();

        foreach ($repositories as $key => $repository) {
            $this->downloadImagesFromRepository($repository);
        }
    }

    /**
     * @return EntityRepository[]
     */
    protected function getRepositories()
    {
        $repositories = [
            'article_image' => $this->getContainer()->get('app.repository.article_image'),
            'block_image' => $this->getContainer()->get('app.repository.block_image'),
            'dealer_image' => $this->getContainer()->get('app.repository.dealer_image'),
            'game_play_image' => $this->getContainer()->get('app.repository.game_play_image'),
            'person_image' => $this->getContainer()->get('app.repository.person_image'),
            'product_box_image' => $this->getContainer()->get('app.repository.product_box_image'),
            'product_variant_image' => $this->getContainer()->get('app.repository.product_variant_image'),
            'pub_banner' => $this->getContainer()->get('app.repository.pub_banner'),
        ];

        $entityOption = $this->input->getOption('entity');

        if (null === $entityOption) {
            return $repositories;
        }

        if (!isset($repositories[$entityOption])) {
            throw new InvalidOptionException(sprintf('Entity with name %s was not found', $entityOption));
        }

        return [$entityOption => $repositories[$entityOption]];
    }

    /**
     * @param EntityRepository $repository
     */
    protected function downloadImagesFromRepository(EntityRepository $repository): void
    {
        $queryBuilder = $repository->createQueryBuilder('o');
        $this->downloadImages($queryBuilder);
    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    protected function downloadImages(QueryBuilder $queryBuilder): void
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
        return $this->input->getOption('image-original-path').$image->getPath();
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
