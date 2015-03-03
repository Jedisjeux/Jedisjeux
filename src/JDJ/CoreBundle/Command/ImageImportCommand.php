<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 03/03/15
 * Time: 19:34
 */

namespace JDJ\CoreBundle\Command;

use Doctrine\ORM\EntityManager;
use JDJ\CoreBundle\Entity\Image;
use JDJ\CoreBundle\Service\ImageImportService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImageImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:image:import')
            ->setDescription('Greet someone')
            ->addArgument('name', InputArgument::OPTIONAL, 'Who do you want to greet?')
            ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ImageImportService $imageImportService */
        $imageImportService = $this->getContainer()->get('app.service.image.import');

        $images = $imageImportService
            ->getImages();

        /** @var Image $image */
        foreach ($images as $image) {

            if (!file_exists($image->getAbsolutePath())) {
                $output->writeln("Downloading image ".$imageImportService->getImageOriginalPath($image));

                $imageImportService
                    ->downloadImage($image);
            }
        }
    }
} 