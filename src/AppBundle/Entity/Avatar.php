<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 14/03/16
 * Time: 13:09
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class Avatar
 *
 * @ORM\Entity
 * @ORM\Table(name="jdj_customer_avatar")
 */
class Avatar extends AbstractImage
{
    /**
     * The path to the  path files
     *
     * @return string
     */
    protected function getUploadDir()
    {
        return 'uploads/avatar';
    }
}
