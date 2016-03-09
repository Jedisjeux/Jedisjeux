<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 04/03/2016
 * Time: 13:34
 */

namespace AppBundle\Command;

use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadProductsCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:products:load')
            ->setDescription('Load all products');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        foreach ($this->getRows() as $data) {
            $output->writeln(sprintf("Loading <comment>%s</comment> product", $data['name']));
            $this->createOrReplaceProduct($data);
        }
    }

    protected function createOrReplaceProduct($data)
    {
        /** @var Product $product */
        $product = $this->getRepository()->findOneBy(array('name' => $data['name']));

        if (null === $product) {
            $product = $this->getFactory()->createNew();
        }
        $data['description'] = !empty($data['description']) ? $this->getHTMLFromText($data['description']) : null;
        $data['joueurMin'] = !empty($data['joueurMin']) ? $data['joueurMin'] : null;
        $data['joueurMax'] = !empty($data['joueurMax']) ? $data['joueurMax'] : null;
        $data['ageMin'] = !empty($data['ageMin']) ? $data['ageMin'] : null;
        $data['materiel'] = !empty($data['materiel']) ? trim($data['materiel']) : null;
        $data['createdAt'] = \DateTime::createFromFormat('Y-m-d H:i:s', $data['createdAt']);
        $data['updatedAt'] = \DateTime::createFromFormat('Y-m-d H:i:s', $data['updatedAt']);
        switch ($data['status']) {
            case 0 :
                $data['status'] = Product::WRITING;
                break;
            case 1 :
                $data['status'] = Product::PUBLISHED;
                break;
            case 2 :
                $data['status'] = Product::NEED_A_TRANSLATION;
                break;
            case 5 :
                $data['status'] = Product::NEED_A_REVIEW;
                break;
            case 3 :
                $data['status'] = Product::READY_TO_PUBLISH;
                break;
        }

        $product->setName($data['name']);
        $product->setDescription($data['description']);
        $product->setCreatedAt($data['createdAt']);
        $product
            ->setCode($data['code'])
            ->setAgeMin($data['ageMin'])
            ->setJoueurMin($data['joueurMin'])
            ->setJoueurMax($data['joueurMax'])
            ->setMateriel($data['materiel'])
            ->setStatus($data['status']);

        $this->getManager()->persist($product);
        $this->getManager()->flush(); // Save changes in database.
        $this->getManager()->clear();
    }

    /**
     * @inheritdoc
     */
    public function getRows()
    {
        $query = <<<EOM
select      concat('game-', old.id) as code,
            old.nom as name,
            old.min as joueurMin,
            old.max as joueurMax,
            old.age_min as ageMin,
            old.presentation as description,
            old.duree as durationMin,
            old.duree as durationMax,
            old.materiel as materiel,
            old.valid as status,
            old.date as createdAt,
            old.date as updatedAt
from        jedisjeux.jdj_game old
where       old.valid in (0, 1, 2, 5, 3)
and         old.id_pere is null
and         old.nom <> ""
EOM;
        $rows = $this->getDatabaseConnection()->fetchAll($query);

        return $rows;
    }

    private function getHTMLFromText($text)
    {
        $text = trim($text);

        /**
         * Turn Double carryage returns into <p>
         */
        $text = "<p>" . preg_replace("/\\n(\\r)?\n/", "</p><p>", $text) . "</p>";

        /**
         * Turn Simple carryage returns into <br />
         */
        $text = "<p>" . preg_replace("/\\n/", "<br />", $text) . "</p>";

        $text = $this->cleanBBCode($text);

        $text = preg_replace("/\<(b|strong)\>/", '</p><h4>', $text);
        $text = preg_replace("/\<\/(b|strong)\>/", '</h4><p>', $text);

        $text = preg_replace("/\<p\>( |\n|\r)*\<br \/>/", '<p>', $text);
        $text = preg_replace("/\<p\>( )*\<\/p\>/", '', $text);

        $text = preg_replace("/\<p\><p\>/", '<p>', $text);
        return $text;
    }

    private function cleanBBCode($text)
    {
        $text = preg_replace("/\[(b):[0-9a-z]+\]/", '</p><h4>', $text);
        $text = preg_replace("/\[(\/)?(b):[0-9a-z]+\]/", '</h4><p>', $text);
        $text = preg_replace("/\[(\/)?(i):[0-9a-z]+\]/", '<${1}i>', $text);
        return $text;
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    protected function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }

    /**
     * @return Factory
     */
    protected function getFactory()
    {
        return $this->getContainer()->get('sylius.factory.product');
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('sylius.repository.product');
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('sylius.manager.product');
    }
}