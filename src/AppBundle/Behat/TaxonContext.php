<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 11/03/2016
 * Time: 15:35
 */

namespace AppBundle\Behat;

use AppBundle\Repository\TaxonRepository;
use Behat\Gherkin\Node\TableNode;
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
            $parents = $repository->findByName($data['parent'], $this->getContainer()->getParameter('locale'));
            if (count($parents) > 0) {
                $parent = $parents[0];
            }

            /** @var TaxonInterface $taxon */
            $taxon = $this->getFactory('taxon')->createNew();
            $taxon->setCode(isset($data['code']) ? $data['code'] : $this->faker->unique()->text(5));
            $taxon->setName(isset($data['name']) ? $data['name'] : $this->faker->name);

            $parent->addChild($taxon);
            $manager->persist($taxon);
            $manager->flush();
        }
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
            $taxon->setCurrentLocale($this->getContainer()->getParameter('locale'));

            $manager->persist($taxon);
            $manager->flush();
        }
    }
}
