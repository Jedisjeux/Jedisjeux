<?php


namespace JDJ\CollectionBundle\Behat;


use Behat\Gherkin\Node\TableNode;
use Doctrine\Common\Util\Debug;
use JDJ\CollectionBundle\Entity\Collection;
use JDJ\CoreBundle\Behat\DefaultContext;

class CollectionContext extends DefaultContext
{
    /**
     * @Given /^there are collections:$/
     * @Given /^there are following collections:$/
     * @Given /^the following collections exist:$/
     */
    public function thereAreCollections(TableNode $table){
        $manager = $this->getEntityManager();

        foreach ($table->getHash() as $data) {

            $user = $this->findOneBy("user", array("username" => $data['username']));

            $collection = new Collection();
            $collection
                ->setName(trim($data['name']))
                ->setDescription(trim($data['description']))
                ->setUser($user)
            ;

            $manager->persist($collection);
        }

        $manager->flush();
    }

}