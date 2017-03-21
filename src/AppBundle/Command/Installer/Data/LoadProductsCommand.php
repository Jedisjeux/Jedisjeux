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
use AppBundle\Entity\Taxon;
use AppBundle\Repository\TaxonRepository;
use AppBundle\TextFilter\Bbcode2Html;
use AppBundle\Updater\ProductCountByTaxonUpdater;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Association\Model\AssociationType;
use Sylius\Component\Product\Factory\ProductFactory;
use Sylius\Component\Resource\Factory\Factory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadProductsCommand extends ContainerAwareCommand
{
    const BATCH_SIZE = 20;

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

//        $i = 0;
//
//        foreach ($this->getRows() as $data) {
//            $output->writeln(sprintf("Loading <comment>%s</comment> product", $data['name']));
//
//            $product = $this->createOrReplaceProduct($data);
//            $this->getManager()->persist($product);
//
//            if (($i % self::BATCH_SIZE) === 0) {
//                $this->getManager()->flush(); // Executes all updates.
//                $this->getManager()->clear(); // Detaches all objects from Doctrine!
//            }
//
//            ++$i;
//        }
//
//        $this->getManager()->flush();
//        $this->getManager()->clear();
//
//        foreach ($this->getVariants() as $data) {
//            $output->writeln(sprintf("Loading <comment>%s</comment> variant for product <comment>%s</comment>",
//                $data['name'],
//                $data['parent_code']
//            ));
//
//            /** @var Product $product */
//            $product = $this->getRepository()->findOneBy(['code' => $data['parent_code']]);
//
//            if (null !== $product) {
//                $this->createOrReplaceProductVariant($product, $data);
//            }
//        }

        $this->deleteProductAssociations();
        $this->insertProductsOfCollections($associationTypeCollection);
        $this->insertProductsOfExpansions($associationTypeExpansion);
    }

    protected function createOrReplaceAssociationTypeCollection()
    {
        /** @var AssociationType $assocationType */
        $assocationType = $this->getContainer()->get('sylius.repository.product_association_type')->findOneBy(['code' => 'collection']);

        if (null === $assocationType) {
            $assocationType = $this->getContainer()->get('sylius.factory.product_association_type')->createNew();
        }

        $assocationType->setCode('collection');
        $assocationType->setName('Dans la même série');

        $this->getManager()->persist($assocationType);
        $this->getManager()->flush(); // Save changes in database.

        return $assocationType;
    }

    /**
     * @return AssociationType
     */
    protected function createOrReplaceAssociationTypeExpansion()
    {
        /** @var AssociationType $associationType */
        $associationType = $this->getContainer()->get('sylius.repository.product_association_type')->findOneBy(['code' => 'expansion']);

        if (null === $associationType) {
            $associationType = $this->getContainer()->get('sylius.factory.product_association_type')->createNew();
        }

        $associationType->setCode('expansion');
        $associationType->setName('Extensions');

        $this->getManager()->persist($associationType);
        $this->getManager()->flush(); // Save changes in database.

        return $associationType;
    }

    /**
     * @param array $data
     *
     * @return Product
     */
    protected function createOrReplaceProduct(array $data)
    {
        /** @var ProductVariant $productVariant */
        $productVariant = $this->getProductVariantRepository()->findOneBy(['code' => $data['code']]);
        /** @var Product $product */
        $product = null !== $productVariant ? $productVariant->getProduct() : null;

        if (null === $product) {
            $product = $this->getFactory()->createWithVariant();
        }

        $bbcode2html = $this->getBbcode2Html();

        $shortDescription = $data['shortDescription'] ?: null;
        $shortDescription = $bbcode2html
            ->setBody($shortDescription)
            ->getFilteredBody();

        $description = $data['description'] ?: null;
        $description = $bbcode2html
            ->setBody($description)
            ->getFilteredBody();

        $data['joueurMin'] = !empty($data['joueurMin']) ? $data['joueurMin'] : null;
        $data['joueurMax'] = !empty($data['joueurMax']) ? $data['joueurMax'] : null;
        $data['ageMin'] = !empty($data['ageMin']) ? $data['ageMin'] : null;
        $data['materiel'] = !empty($data['materiel']) ? trim($data['materiel']) : null;
        $data['createdAt'] = \DateTime::createFromFormat('Y-m-d H:i:s', $data['createdAt']);
        $data['updatedAt'] = \DateTime::createFromFormat('Y-m-d H:i:s', $data['updatedAt']);
        $product->getFirstVariant()->setOldHref(!empty($data['href']) ? $data['href'] : null);
        $data['releasedAt'] = $data['releasedAt'] ? \DateTime::createFromFormat('Y-m-d', $data['releasedAt']) : null;
        $product->getFirstVariant()->setReleasedAtPrecision($this->getReleasedAtPrecision($data));
        if (null !== $data['releasedAt']) {
            if (ProductVariant::RELEASED_AT_PRECISION_ON_MONTH === $product->getMasterVariant()->getReleasedAtPrecision()) {
                $data['releasedAt'] = $data['releasedAt']->add(new \DateInterval('P1D'));
            } elseif (ProductVariant::RELEASED_AT_PRECISION_ON_YEAR === $product->getMasterVariant()->getReleasedAtPrecision()) {
                $data['releasedAt'] = $data['releasedAt']->add(new \DateInterval('P1D'))->add(new \DateInterval('P1M'));
            }
        }

        switch ($data['status']) {
            case 0 :
                $data['status'] = Product::STATUS_NEW;
                break;
            case 1 :
                $data['status'] = Product::PUBLISHED;
                break;
            case 2 :
                $data['status'] = Product::PENDING_REVIEW;
                break;
            case 5 :
                $data['status'] = Product::PENDING_REVIEW;
                break;
            case 3 :
                $data['status'] = Product::PENDING_PUBLICATION;
                break;
        }

        $product->setName(trim($data['name']));
        $product->setSlug(null); // enforce slug to be updated
        $product->setDescription(trim($description));
        $product->setCreatedAt($data['createdAt']);
        $product->setUpdatedAt($data['updatedAt']);
        $product->setReleasedAt($data['releasedAt']);

        $product
            ->setCode($data['code'])
            ->setShortDescription(trim($shortDescription))
            ->setAgeMin($data['ageMin'])
            ->setJoueurMin($data['joueurMin'])
            ->setJoueurMax($data['joueurMax'])
            ->setMateriel($data['materiel'])
            ->setStatus($data['status']);

        $product->getMasterVariant()->setCode($data['code']);
        $product->getMasterVariant()->setName($data['name']);

        return $product;
    }

    /**
     * @param $data
     *
     * @return null|string
     */
    protected function getReleasedAtPrecision(array $data)
    {
        switch ($data['releasedAtPrecision']) {
            case 'jour' :
                return ProductVariant::RELEASED_AT_PRECISION_ON_DAY;
            case 'mois' :
                return ProductVariant::RELEASED_AT_PRECISION_ON_MONTH;
            case 'annee' :
                return ProductVariant::RELEASED_AT_PRECISION_ON_YEAR;
        }

        return null;
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

    /**
     * @param AssociationType $associationType
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function insertProductsOfCollections(AssociationType $associationType)
    {
        $query = <<<EOM
insert into sylius_product_association(product_id, association_type_id, created_at)
select      product.id,
  :association_type_id,
  now()
from        sylius_product product
  inner join jedisjeux.jdj_game old
    on concat('game-', old.id) = product.code  
where  (old.id_pere is null or type_diff = 'collection')
and exists (
    select 0
    from  jedisjeux.jdj_game a
    where a.id_famille = old.id_famille
    and   a.type_diff = 'collection'
)
EOM;

        $this->getManager()->getConnection()->executeQuery($query, ['association_type_id' => $associationType->getId()]);

        $query = <<<EOM
insert into sylius_product_association_product(association_id, product_id)
select association.id, associated.id
from sylius_product_association association
inner join sylius_association_type associationType
  on associationType.id = association.association_type_id
inner join sylius_product product
  on product.id = association.product_id
inner join jedisjeux.jdj_game old
  on concat('game-', old.id) = product.code
inner join jedisjeux.jdj_game oldAssociated
  on oldAssociated.id_famille = old.id_famille
inner join sylius_product associated
  on associated.code = concat('game-', oldAssociated.id)
where associationType.id = :association_type_id
and (oldAssociated.id_pere is null or oldAssociated.type_diff = 'collection')
and old.id <> oldAssociated.id
EOM;

        $this->getManager()->getConnection()->executeQuery($query, ['association_type_id' => $associationType->getId()]);

    }

    /**
     * @param AssociationType $assocationType
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function insertProductsOfExpansions(AssociationType $assocationType)
    {
        $query = <<<EOM
insert into sylius_product_association(product_id, association_type_id, created_at)
SELECT DISTINCT
  parent.product_id,
  :association_type_id,
  now()
FROM jedisjeux.jdj_game old
  INNER JOIN sylius_product_variant parent
    ON parent.code = concat('game-', old.id_pere)
WHERE type_diff = 'extension'
EOM;

        $this->getManager()->getConnection()->executeQuery($query, ['association_type_id' => $assocationType->getId()]);

        $query = <<<EOM
insert into sylius_product_association_product(association_id, product_id)
SELECT
  association.id,
  associatedVariant.product_id
FROM sylius_product_association association
  INNER JOIN sylius_product_variant baseVariant
    ON baseVariant.product_id = association.product_id
  INNER JOIN jedisjeux.jdj_game old
    ON concat('game-', old.id_pere) = baseVariant.code
       AND old.type_diff = 'extension'
  INNER JOIN sylius_product_variant associatedVariant
    on associatedVariant.code = concat('game-', old.id)
WHERE association.association_type_id = :association_type_id
EOM;

        $this->getManager()->getConnection()->executeQuery($query, ['association_type_id' => $assocationType->getId()]);

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
        $productVariant->setName($data['name']);
        $productVariant->setOldHref(!empty($data['href']) ? $data['href'] : null);
        $productVariant->setCreatedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $data['createdAt']));
        $productVariant->setReleasedAt($data['releasedAt'] ? \DateTime::createFromFormat('Y-m-d', $data['releasedAt']) : null);
        $productVariant->setReleasedAtPrecision($this->getReleasedAtPrecision($data));

        $product->addVariant($productVariant);

        $this->getManager()->persist($product);
        $this->getManager()->flush();
        $this->getManager()->clear();
    }

    /**
     * @return array
     */
    public function getRows()
    {
        $query = <<<EOM
SELECT
  concat('game-', old.id) AS code,
  old.nom                 AS name,
  old.min                 AS joueurMin,
  old.max                 AS joueurMax,
  old.age_min             AS ageMin,
  old.intro               AS shortDescription,
  old.presentation        AS description,
  old.duree               AS durationMin,
  old.duree               AS durationMax,
  old.materiel            AS materiel,
  old.valid               AS status,
  old.date                AS createdAt,
  old.date                AS updatedAt,
  old.date_sortie         AS releasedAt,
  old.precis_sortie       AS releasedAtPrecision,
  old.href                AS href
FROM jedisjeux.jdj_game old
WHERE old.valid IN (0, 1, 2, 5, 3)
      AND (old.id_pere IS NULL OR type_diff IN ('extension', 'collection'))
      AND old.nom <> ""
EOM;

        return $this->getManager()->getConnection()->fetchAll($query);
    }

    /**
     * @return array
     */
    private function getVariants()
    {
        $query = <<<EOM
SELECT
  concat('game-', old.id)      AS code,
  concat('game-', old.id_pere) AS parent_code,
  old.nom                      AS name,
  old.min                      AS joueurMin,
  old.max                      AS joueurMax,
  old.age_min                  AS ageMin,
  old.intro                    AS shortDescription,
  old.presentation             AS description,
  old.duree                    AS durationMin,
  old.duree                    AS durationMax,
  old.materiel                 AS materiel,
  old.valid                    AS status,
  old.date                     AS createdAt,
  old.date                     AS updatedAt,
  old.date_sortie              AS releasedAt,
  old.precis_sortie            AS releasedAtPrecision,
  old.href                     AS href
FROM jedisjeux.jdj_game old
WHERE old.valid IN (0, 1, 2, 5, 3)
      AND old.id_pere IS NOT NULL
      AND old.nom <> ""
      AND type_diff IN ('regle', 'materiel')

EOM;

        return $this->getManager()->getConnection()->fetchAll($query);

    }

    /**
     * @return Bbcode2Html|object
     */
    protected function getBbcode2Html()
    {
        return $this->getContainer()->get('app.text.filter.bbcode2html');
    }

    /**
     * @return ProductFactory|object
     */
    protected function getFactory()
    {
        return $this->getContainer()->get('sylius.factory.product');
    }

    /**
     * @return Factory|object
     */
    protected function getProductVariantFactory()
    {
        return $this->getContainer()->get('sylius.factory.product_variant');
    }

    /**
     * @return EntityRepository|object
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('sylius.repository.product');
    }

    /**
     * @return EntityRepository|object
     */
    protected function getProductVariantRepository()
    {
        return $this->getContainer()->get('sylius.repository.product_variant');
    }

    /**
     * @return TaxonRepository|object
     */
    protected function getTaxonRepository()
    {
        return $this->getContainer()->get('sylius.repository.taxon');
    }

    /**
     * @return EntityManager|object
     */
    protected function getManager()
    {
        return $this->getContainer()->get('sylius.manager.product');
    }
}