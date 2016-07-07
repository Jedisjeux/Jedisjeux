<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 04/03/2016
 * Time: 13:34
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Entity\Product;
use AppBundle\Entity\ProductVariant;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Association\Model\AssociationType;
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

        $associationTypeCollection = $this->createOrReplaceAssociationTypeCollection();
        $associationTypeExpansion = $this->createOrReplaceAssociationTypeExpansion();

        foreach ($this->getRows() as $data) {
            $output->writeln(sprintf("Loading <info>%s</info> product", $data['name']));
            $this->createOrReplaceProduct($data);
        }

        foreach ($this->getVariants() as $data) {
            $output->writeln(sprintf("Loading <info>%s</info> variant for product <info>%s</info>",
                $data['name'],
                $data['parent_code']
            ));

            /** @var Product $product */
            $product = $this->getRepository()->findOneBy(['code' => $data['parent_code']]);

            if (null !== $product) {
                $this->createOrReplaceProductVariant($product, $data);
            }
        }

        $this->deleteProductAssociations();
        $this->insertProductsOfCollections($associationTypeCollection);
        $this->insertProductsOfExpansions($associationTypeExpansion);
        $this->recalculateMasters();
    }

    protected function createOrReplaceAssociationTypeCollection()
    {
        /** @var AssociationType $assocationType */
        $assocationType = $this->getContainer()->get('sylius.repository.product_association_type')->findOneBy(['code' => 'collection']);

        if (null === $assocationType) {
            $assocationType = $this->getContainer()->get('sylius.factory.product_association_type')->createNew();
        }

        $assocationType->setCode('collection');
        $assocationType->setName('Dans la même collection');

        $this->getManager()->persist($assocationType);
        $this->getManager()->flush(); // Save changes in database.

        return $assocationType;
    }

    protected function createOrReplaceAssociationTypeExpansion()
    {
        /** @var AssociationType $assocationType */
        $assocationType = $this->getContainer()->get('sylius.repository.product_association_type')->findOneBy(['code' => 'expansion']);

        if (null === $assocationType) {
            $assocationType = $this->getContainer()->get('sylius.factory.product_association_type')->createNew();
        }

        $assocationType->setCode('expansion');
        $assocationType->setName('Extensions');

        $this->getManager()->persist($assocationType);
        $this->getManager()->flush(); // Save changes in database.

        return $assocationType;
    }

    protected function createOrReplaceProduct($data)
    {
        /** @var Product $product */
        $product = $this->getRepository()->findOneBy(array('code' => $data['code']));

        if (null === $product) {
            $product = $this->getFactory()->createNew();
        }
        $data['shortDescription'] = !empty($data['shortDescription']) ? $this->getHTMLFromText($data['shortDescription']) : null;
        $data['description'] = !empty($data['description']) ? $this->getHTMLFromText($data['description']) : null;
        $data['joueurMin'] = !empty($data['joueurMin']) ? $data['joueurMin'] : null;
        $data['joueurMax'] = !empty($data['joueurMax']) ? $data['joueurMax'] : null;
        $data['ageMin'] = !empty($data['ageMin']) ? $data['ageMin'] : null;
        $data['materiel'] = !empty($data['materiel']) ? trim($data['materiel']) : null;
        $data['createdAt'] = \DateTime::createFromFormat('Y-m-d H:i:s', $data['createdAt']);
        $data['updatedAt'] = \DateTime::createFromFormat('Y-m-d H:i:s', $data['updatedAt']);
        $data['releasedAt'] = $data['releasedAt'] ? \DateTime::createFromFormat('Y-m-d', $data['releasedAt']) : null;
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
        $product->setUpdatedAt($data['updatedAt']);
        $product->setReleasedAt($data['releasedAt']);
        $product
            ->setCode($data['code'])
            ->setShortDescription($data['shortDescription'])
            ->setAgeMin($data['ageMin'])
            ->setJoueurMin($data['joueurMin'])
            ->setJoueurMax($data['joueurMax'])
            ->setMateriel($data['materiel'])
            ->setStatus($data['status']);

        $product->getMasterVariant()->setCode($data['code']);

        $this->getManager()->persist($product);
        $this->getManager()->flush(); // Save changes in database.
        $this->getManager()->clear();
    }

    protected function deleteProductAssociations()
    {
        /** @var EntityRepository $repository */
        $repository = $this->getContainer()->get('sylius.repository.product_association');

        $queryBuilder = $repository->createQueryBuilder('o');
        $queryBuilder
            ->delete();

        $queryBuilder->getQuery()->execute();
    }

    protected function insertProductsOfCollections(AssociationType $assocationType)
    {
        $query = <<<EOM
insert into sylius_product_association(product_id, association_type_id, created_at)
select      product.id,
  :association_type_id,
  now()
from        sylius_product product
  inner join jedisjeux.jdj_game old
    on concat('game-', old.id) = product.code
where       old.id_pere is null
and exists (
    select 0
    from  jedisjeux.jdj_game a
    where a.id_famille = old.id
    and   a.type_diff = 'collection'
)
EOM;

        $this->getDatabaseConnection()->executeQuery($query, ['association_type_id' => $assocationType->getId()]);

        $query = <<<EOM
insert into sylius_product_association_product(association_id, product_id)
select      association.id, associated.id
from        sylius_product_association association
inner JOIN sylius_product product
             on product.id = association.product_id
inner JOIN jedisjeux.jdj_game old
              on concat('game-', id_famille) = product.code
inner JOIN sylius_product associated
              on associated.code = concat('game-', old.id)
where     old.id_famille <> old.id
and       old.type_diff = 'collection'
and       association.association_type_id = :association_type_id
EOM;

        $this->getDatabaseConnection()->executeQuery($query, ['association_type_id' => $assocationType->getId()]);

    }

    protected function insertProductsOfExpansions(AssociationType $assocationType)
    {
        $query = <<<EOM
insert into sylius_product_association(product_id, association_type_id, created_at)
select distinct parent.id,
        :association_type_id,
        now()
from   jedisjeux.jdj_game old
  inner join sylius_product parent
    on parent.code = concat('game-', old.id_pere)
where type_diff = 'extension'
EOM;

        $this->getDatabaseConnection()->executeQuery($query, ['association_type_id' => $assocationType->getId()]);

        $query = <<<EOM
insert into sylius_product_association_product(association_id, product_id)
select      association.id, associated.id
from        sylius_product_association association
inner JOIN sylius_product product
             on product.id = association.product_id
inner JOIN jedisjeux.jdj_game old
              on concat('game-', id_famille) = product.code
inner JOIN sylius_product associated
              on associated.code = concat('game-', old.id)
where     old.id_famille <> old.id
and       association.association_type_id = :association_type_id
and       old.type_diff = 'extension'
EOM;

        $this->getDatabaseConnection()->executeQuery($query, ['association_type_id' => $assocationType->getId()]);

    }

    /**
     * @param Product $product
     * @param array $data
     */
    protected function createOrReplaceProductVariant(Product $product, array $data)
    {
        /** @var ProductVariant $productVariant */
        $productVariant = $this->getProductVariantRepository()->findOneBy(['code' => $data['code']]);

        if (null === $productVariant) {
            /** @var ProductVariant $productVariant */
            $productVariant = $this->getProductVariantFactory()->createNew();
        }

        $productVariant->setCode($data['code']);
        $productVariant->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $data['createdAt']));
        $productVariant->setReleasedAt($data['releasedAt'] ? \DateTime::createFromFormat('Y-m-d', $data['releasedAt']) : null);

        $product->addVariant($productVariant);

        $this->getManager()->persist($product);
        $this->getManager()->flush();
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
            old.intro as shortDescription,
            old.presentation as description,
            old.duree as durationMin,
            old.duree as durationMax,
            old.materiel as materiel,
            old.valid as status,
            old.date as createdAt,
            old.date as updatedAt,
            old.date_sortie as releasedAt
from        jedisjeux.jdj_game old
where       old.valid in (0, 1, 2, 5, 3)
and         (old.id_pere is null or type_diff in ('extension', 'collection'))
and         old.nom <> ""
EOM;
        return $this->getDatabaseConnection()->fetchAll($query);
    }

    private function getVariants()
    {
        $query = <<<EOM
select      concat('game-', old.id) as code,
            concat('game-', old.id_pere ) as parent_code,
            old.nom as name,
            old.min as joueurMin,
            old.max as joueurMax,
            old.age_min as ageMin,
            old.intro as shortDescription,
            old.presentation as description,
            old.duree as durationMin,
            old.duree as durationMax,
            old.materiel as materiel,
            old.valid as status,
            old.date as createdAt,
            old.date as updatedAt,
            old.date_sortie as releasedAt
from        jedisjeux.jdj_game old
where       old.valid in (0, 1, 2, 5, 3)
            and         old.id_pere is not null
            and         old.nom <> ""
            and type_diff in ('regle', 'materiel')
EOM;

        return $this->getDatabaseConnection()->fetchAll($query);

    }

    protected function recalculateMasters()
    {
        // first, set all variants as master
        $query = <<<EOM
update sylius_product_variant variant
 set variant.is_master = 1;
EOM;

        $this->getDatabaseConnection()->executeQuery($query);

        // only keep the most recent id as master
        $query = <<<EOM
        update sylius_product_variant variant
  inner JOIN sylius_product_variant other_variant
    on other_variant.product_id = variant.product_id
set variant.is_master = 0
where variant.id < other_variant.id;
EOM;

        $this->getDatabaseConnection()->executeQuery($query);

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
     * @return Factory
     */
    protected function getProductVariantFactory()
    {
        return $this->getContainer()->get('sylius.factory.product_variant');
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('sylius.repository.product');
    }

    /**
     * @return EntityRepository
     */
    protected function getProductVariantRepository()
    {
        return $this->getContainer()->get('sylius.repository.product_variant');
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('sylius.manager.product');
    }
}