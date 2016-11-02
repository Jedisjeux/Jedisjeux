<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 14/03/2016
 * Time: 11:14
 */

namespace AppBundle\Behat;

use AppBundle\Entity\Product;
use Behat\Gherkin\Node\TableNode;
use Sylius\Component\Attribute\AttributeType\CheckboxAttributeType;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class ProductContext extends DefaultContext
{
    /**
     * @Given /^there are products:$/
     * @Given /^there are following products:$/
     * @Given /^the following products exist:$/
     *
     * @param TableNode $table
     */
    public function thereAreProducts(TableNode $table)
    {
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {

            /** @var TaxonInterface $mainTaxon */
            $mainTaxon = null;

            if (isset($data['main-taxon'])) {
                $mainTaxon = $this->getRepository('taxon')->findOneByName($data['main-taxon']);
            }

            /** @var Product $product */
            $product = $this->getFactory('product')->createWithVariant();
            $product->setCode($this->faker->unique()->postcode);
            $product->setName(isset($data['name']) ? $data['name'] : $this->faker->name);
            $product->setDescription(isset($data['description']) ? $data['description'] : $this->faker->realText());
            $product->setMainTaxon($mainTaxon);

            $manager->persist($product);
        }

        $manager->flush();
    }

    /**
     * @Given /^product "([^"]*)" has following attributes:$/
     */
    public function productHasAttributes($productName, TableNode $table)
    {
        /** @var ProductInterface $product */
        $product = $this->findOneByName('product', $productName);

        foreach ($table->getHash() as $data) {
            /**
             * @var AttributeInterface $productAttribute
             * @var AttributeValueInterface $attributeValue
             */
            $productAttribute = $this->findOneByName('product_attribute', trim($data['name']));
            $attributeValue = $this->getFactory('product_attribute_value')->createNew();

            $attributeValue->setAttribute($productAttribute);
            $attributeValue->setValue($data['value']);

            $product->addAttribute($attributeValue);
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @Given /^there are following attributes:$/
     * @Given /^the following attributes exist:$/
     */
    public function thereAreAttributes(TableNode $table)
    {
        foreach ($table->getHash() as $attribute) {
            $this->thereIsAttribute(
                $attribute['name'],
                $attribute['type'],
                (isset($attribute['code'])) ? $attribute['code'] : null,
                (isset($attribute['configuration'])) ? $attribute['configuration'] : null
            );
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @Given /^There is attribute "([^""]*)" with type "([^""]*)"$/
     * @Given /^I created attribute "([^""]*)" with type "([^""]*)"$/
     */
    public function thereIsAttribute($name, $type, $code = null, $configuration = null)
    {
        $code = (null === $code) ? strtolower(str_replace(' ', '_', $name)) : $code;
        $storageType = (CheckboxAttributeType::TYPE === $type) ? 'boolean' : $type;

        $attribute = $this->getFactory('product_attribute')->createNew();
        $attribute->setName($name);
        $attribute->setType($type);
        $attribute->setCode($code);
        $attribute->setStorageType($storageType);

        if (null !== $configuration && '' !== $configuration) {
            $attribute->setConfiguration($this->getConfiguration($configuration));
        }

        $manager = $this->getEntityManager();
        $manager->persist($attribute);

        return $attribute;
    }

    /**
     * @Given /^product "([^"]*)" has following taxons:$/
     */
    public function productHasTaxons($productName, TableNode $table)
    {
        /** @var Product $product */
        $product = $this->findOneByName('product', $productName);

        foreach ($table->getHash() as $data) {
            /** @var TaxonInterface $parent */
            $taxon = $this->getRepository('taxon')->findOneByName($data['name']);
            $product->addTaxon($taxon);
        }

        $this->getEntityManager()->flush();
    }

    /**
     * @When /^I request get "([^"]*)" product$/
     */
    public function iRequestGetProduct($productName)
    {
        /** @var Product $product */
        $product = $this->findOneByName('product', $productName);

        $this->makeRequest(sprintf('/api/products/%s', $product->getId()));
    }
}
