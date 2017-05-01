<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Entity\ProductBox;
use AppBundle\Entity\ProductVariant;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadBoxesOfProductsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:boxes-of-products:load')
            ->setDescription('Load boxes of all products')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command loads boxes of all products.
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        foreach ($this->getBoxes() as $data) {
            $output->writeln(sprintf("Load box of <comment>%s</comment> variant", $data['variant_name']));

            $box = $this->createOrReplaceBox($data);

            $this->getManager()->persist($box);
            $this->getManager()->flush();
            $this->getManager()->clear();
        }

        $output->writeln(sprintf("<info>%s</info>", "Boxes of all products have been successfully loaded."));
    }

    /**
     * @param array $data
     *
     * @return ProductBox
     */
    protected function createOrReplaceBox(array $data)
    {
        /** @var ProductVariant $productVariant */
        $productVariant = $this->getProductVariantRepository()->find($data['variant_id']);

        if (null === $productVariant) {
            throw new NotFoundHttpException(sprintf('product variant with name %s was not found', $data['variant_name']));
        }

        if (null === $box = $productVariant->getBox()) {
            /** @var ProductBox $box */
            $box = $this->getFactory()->createNew();
            $productVariant->setBox($box);

            $boxImage = $this->getProductBoxImageFactory()->createNew();
            $box->setImage($boxImage);
        }

        $box->getImage()->setPath($data['path']);
        $box->setHeight($data['height']);

        return $box;
    }

    /**
     * @return array
     */
    protected function getBoxes()
    {
        $query = <<<EOM
SELECT
  variant.id       AS variant_id,
  variantTranslation.name     as variant_name,
  oldImage.img_nom AS path,
  oldImage.img_height as height
FROM jedisjeux.jdj_images_elements oldImageElement
  INNER JOIN jedisjeux.jdj_images oldImage
    ON oldImage.img_id = oldImageElement.img_id
  INNER JOIN sylius_product_variant variant
    ON variant.code = concat('game-', oldImageElement.elem_id)
  INNER JOIN sylius_product_variant_translation variantTranslation
  on variantTranslation.translatable_id = variant.id
  and variantTranslation.locale = 'fr_FR'
WHERE oldImageElement.elem_type = 'ludovirtuelle';
EOM;

        return $this->getManager()->getConnection()->fetchAll($query);
    }

    /**
     * @return object|FactoryInterface
     */
    protected function getFactory()
    {
        return $this->getContainer()->get('app.factory.product_box');
    }

    /**
     * @return object|FactoryInterface
     */
    protected function getProductBoxImageFactory()
    {
        return $this->getContainer()->get('app.factory.product_box_image');
    }

    /**
     * @return object|EntityRepository
     */
    protected function getProductVariantRepository()
    {
        return $this->getContainer()->get('sylius.repository.product_variant');
    }

    /**
     * @return object|EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
