<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Taxonomy\Model\TaxonTranslation as BaseTaxonTranslation;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ORM\Entity
 * @ORM\Table(name="sylius_taxon_translation", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="slug_uidx", columns={"locale", "slug"})
 * }))
 */
class TaxonTranslation extends BaseTaxonTranslation
{

}
