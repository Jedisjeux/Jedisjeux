<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 03/03/15
 * Time: 20:09
 */

namespace JDJ\CoreBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use JDJ\CoreBundle\Entity\Image;

/**
 * Class ImageImportService
 * @package JDJ\CoreBundle\Service
 */
class ImageImportService
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public function getImages()
    {
        return $this
            ->entityManager
            ->getRepository('JDJCoreBundle:Image')
            ->findAll();
    }

    /**
     * @param Image $image
     */
    public function downloadImage(Image $image)
    {
        file_put_contents($image->getAbsolutePath(), file_get_contents($this->getImageOriginalPath($image)));
    }

    /**
     * @param Image $image
     * @return string
     */
    public function getImageOriginalPath(Image $image)
    {
        return "http://www.jedisjeux.net/img/800/".$image->getPath();
    }
} 