<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 11/03/2016
 * Time: 15:35.
 */

namespace App\Behat;

use App\Entity\Taxon;
use App\Repository\TaxonRepository;
use Behat\Gherkin\Node\TableNode;
use Sylius\Component\Taxonomy\Generator\TaxonSlugGeneratorInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author LoÃ¯c FrÃ©mont <loic@mobizel.com>
 */
class TaxonContext extends DefaultContext
{
    /**
     * @Given /^there are taxons:$/
     * @Given /^there are following taxons:$/
     * @Given /^the following taxons exist:$/
     *
     * @param TableNode $table
     */
    public function thereAreTaxons(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            /** @var TaxonRepository $repository */
            $repository = $this->getRepository('taxon');

            /** @var TaxonInterface $parent */
            $parent = $repository->findOneBySlug($data['parent'], $this->getContainer()->getParameter('locale'));

            if (null === $parent) {
                throw new \InvalidArgumentException(
                    sprintf('Taxon with slug "%s" was not found.', $data['parent'])
                );
            }

            /** @var Taxon $taxon */
            $taxon = $this->getFactory('taxon')->createNew();
            $taxon->setCode(isset($data['code']) ? $data['code'] : $this->faker->unique()->text(5));
            $taxon->setName(isset($data['name']) ? $data['name'] : $this->faker->name);
            $taxon->setPublic(isset($data['public']) ? (bool) $data['public'] : true);

            $parent->addChild($taxon);
            $taxon->setSlug($this->getTaxonSlugGenerator()->generate($taxon));

            $manager->persist($taxon);
            $manager->flush();
        }
    }

    /**
     * @return TaxonSlugGeneratorInterface|object
     */
    protected function getTaxonSlugGenerator()
    {
        return $this->getContainer()->get('sylius.generator.taxon_slug');
    }

    /**
     * @Given /^there are root taxons:$/
     * @Given /^there are following toot taxons:$/
     * @Given /^the following root taxons exist:$/
     *
     * @param TableNode $table
     */
    public function thereAreRootTaxons(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {
            /** @var TaxonInterface $taxon */
            $taxon = $this->getFactory('taxon')->createNew();
            $taxon->setCode(isset($data['code']) ? $data['code'] : $this->faker->unique()->text(5));
            $taxon->setName(isset($data['name']) ? $data['name'] : $this->faker->name);
            $taxon->setSlug($this->getTaxonSlugGenerator()->generate($taxon));

            $manager->persist($taxon);
            $manager->flush();
        }
    }
}
