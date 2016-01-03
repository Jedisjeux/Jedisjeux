<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 03/03/15
 * Time: 19:34
 */

namespace AppBundle\Command;

use JDJ\CoreBundle\Entity\Image;
use JDJ\CoreBundle\Service\ImageImportService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DownloadImageCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:images:download')
            ->setDescription('Download images')
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
                $output->writeln('Downloading image <comment>'.$imageImportService->getImageOriginalPath($image).'</comment>');

                $imageImportService
                    ->downloadImage($image);
            }
        }
    }
}