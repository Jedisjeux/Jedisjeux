<?php
/**
 * Created by PhpStorm.
 * User: loic_425
 * Date: 07/03/16
 * Time: 16:46
 */

namespace AppBundle\Command;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use JDJ\CoreBundle\Entity\Image;
use JDJ\JeuBundle\Entity\Jeu;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadImagesOfProductsCommand extends ContainerAwareCommand
{
    /**
     * @var ObjectManager
     */
    private $manager;

    protected $output;

    protected function configure()
    {
        $this
            ->setName('app:images-of-products:load')
            ->setDescription('Loading images of products');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $manager */
        $manager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->load($manager);
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getDatabaseConnection()
    {
        return $this->getContainer()->get('database_connection');
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $query = <<<EOM
delete from sylius_product_variant_image
EOM;

        $this->getDatabaseConnection()->executeQuery($query);

        $query = <<<EOM
insert into sylius_product_variant_image (id, variant_id, path, description, is_main, is_material)
  select      distinct old.img_id, variant.id, img_nom, ie.legende, ie.main,
                case
                    when ie.ordre = 1 then 1
                    else 0
                end as is_material
  from        jedisjeux.jdj_images old
    inner join  jedisjeux.jdj_images_elements ie
      on ie.img_id = old.img_id
         and ie.elem_type = 'jeu'
    inner join  sylius_product product
      on product.code = concat('game-', ie.elem_id)
    inner join  sylius_product_variant variant
      on variant.product_id = product.id
  where variant.is_master = 1
EOM;

        $this->getDatabaseConnection()->executeQuery($query);


    }
} 