<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 15/03/2016
 * Time: 09:47
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Review\Model\Review;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_product_review")
 */
class ProductReview extends Review
{
    /**
     * ProductReview constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->title = '';
    }
}