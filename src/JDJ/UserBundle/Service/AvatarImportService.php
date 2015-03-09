<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 03/03/15
 * Time: 20:09
 */

namespace JDJ\UserBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use JDJ\CoreBundle\Entity\Image;
use JDJ\UserBundle\Entity\Avatar;
use JDJ\UserBundle\Entity\User;

/**
 * Class AvatarImportService
 * @package JDJ\UserBundle\Service
 */
class AvatarImportService
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
    public function getAvatars()
    {
        return $this
            ->entityManager
            ->getRepository('JDJUserBundle:Avatar')
            ->findAll();
    }

    /**
     * @param Avatar $avatar
     */
    public function downloadAvatar(Avatar $avatar)
    {
        file_put_contents($avatar->getAbsolutePath(), file_get_contents($this->getAvatarOriginalPath($avatar)));
    }

    /**
     * @param Avatar $avatar
     * @return string
     */
    public function getAvatarOriginalPath(Avatar $avatar)
    {
        return "http://www.jedisjeux.net/phpbb3/download/file.php?avatar=".$avatar->getPath();
    }
} 