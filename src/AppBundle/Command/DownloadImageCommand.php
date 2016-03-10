<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 03/03/15
 * Time: 19:34
 */

namespace AppBundle\Command;

use AppBundle\Entity\AbstractImage;
use Doctrine\ORM\EntityRepository;
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
        /** @var EntityRepository $repository */
        $repository = $this->getContainer()->get('app.repository.product_variant_image');

        $images = $repository
            ->findAll();

        /** @var AbstractImage $image */
        foreach ($images as $image) {

            if (!file_exists($image->getAbsolutePath())) {
                $output->writeln('Downloading image <comment>'.$this->getImageOriginalPath($image).'</comment>');

                $this->downloadImage($image);
            }
        }
    }

    /**
     * @param AbstractImage $image
     * @return string
     */
    protected function getImageOriginalPath(AbstractImage $image)
    {
        return "http://www.jedisjeux.net/img/800/".$image->getPath();
    }

    /**
     * @param AbstractImage $image
     */
    protected function downloadImage(AbstractImage $image)
    {
        file_put_contents($image->getAbsolutePath(), file_get_contents($this->getImageOriginalPath($image)));
    }
}