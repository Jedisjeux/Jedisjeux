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
            ->getRepository('JDJUserBundle:User')
            ->findAll();
    }

    /**
     * @param User $user
     */
    public function downloadAvatar(User $user)
    {
        file_put_contents($user->getAbsolutePath(), file_get_contents($this->getAvatarOriginalPath($user)));
    }

    /**
     * @param User $user
     * @return string
     */
    public function getAvatarOriginalPath(User $user)
    {
        return "http://www.jedisjeux.net/phpbb3/download/file.php?avatar=".$user->getAvatar();
    }
} 