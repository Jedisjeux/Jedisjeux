<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 03/03/16
 * Time: 08:05
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Taxonomy\Model\TaxonTranslation as BaseTaxonTranslation;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_taxon_translation", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="permalink_uidx", columns={"locale", "permalink"})}))
 */
class TaxonTranslation extends BaseTaxonTranslation
{

}