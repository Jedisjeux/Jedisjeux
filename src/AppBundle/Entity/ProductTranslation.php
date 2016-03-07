<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 07/03/2016
 * Time: 13:56
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Product\Model\ProductTranslation as BaseProductTranslation;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_product_translation")
 */
class ProductTranslation extends BaseProductTranslation
{

}