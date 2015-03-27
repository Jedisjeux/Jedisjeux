<?php


namespace JDJ\CollectionBundle\Behat;


use Behat\Gherkin\Node\TableNode;
use Doctrine\Common\Util\Debug;
use JDJ\CollectionBundle\Entity\Collection;
use JDJ\CollectionBundle\Entity\ListElement;
use JDJ\CoreBundle\Behat\DefaultContext;
use JDJ\WebBundle\Entity\Statut;

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

    /**
     * @Given /^collection "([^""]*)" has following games:$/
     */
    public function collectionHasFollowingGames($collectionLibelle, TableNode $gameTable)
    {
        $manager = $this->getEntityManager();

        /** @var Collection $collection */
        $collection = $this->findOneBy("collection", array("name" => $collectionLibelle));

        foreach ($gameTable->getRows() as $node) {

            $gameLibelle = $node[0];

            $jeu = $this->findOneBy("jeu", array("libelle" => $gameLibelle));

            $listElement = new ListElement();
            $listElement->setCollection($collection);
            $listElement->setJeu($jeu);

            /** persist */
            $manager->persist($listElement);
            $manager->flush();

        }


    }

}