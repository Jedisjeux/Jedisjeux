<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Entity\Taxon;
use AppBundle\Repository\TaxonRepository;
use Doctrine\ORM\EntityManager;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\Taxonomy\Generator\TaxonSlugGeneratorInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadTaxonsOfTopicsCommand extends ContainerAwareCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:taxons-of-topics:load')
            ->setDescription('Load taxons of topics');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        $rootTaxon = $this->getRootTaxon();

        foreach ($this->getTaxons() as $data) {
            $output->writeln(sprintf("Load <info>%s</info> taxon", $data['name']));
            $this->createOrReplaceTaxon($data, $rootTaxon);
        }

        $this->updatePrivateTaxons();
        $this->getManager()->clear();
    }

    /**
     * @param array $data
     * @param TaxonInterface $rootTaxon
     *
     * @return TaxonInterface
     */
    protected function createOrReplaceTaxon(array $data, TaxonInterface $rootTaxon)
    {
        $code = 'forum-'.$data['id'];

        /** @var TaxonInterface $taxon */
        $taxon = $this->getRepository()->findOneBy(['code' => $code]);

        if (null === $taxon) {
            $taxon = $this->getFactory()->createNew();
        }

        $parentTaxonCode = $this->findParentTaxonCode($data['tree_left'], $data['tree_right']);
        $parentTaxon = $this->getRepository()->findOneBy(['code' => $parentTaxonCode]);

        $taxon->setCode($code);
        $taxon->setName($data['name']);
        $taxon->setDescription($data['description']);
        $taxon->setParent($parentTaxon);

        if (null === $parentTaxon) {
            $rootTaxon->addChild($taxon);
        }

        $taxon->setSlug($this->getTaxonSlugGenerator()->generate($taxon));

        $this->getManager()->persist($taxon);
        $this->getManager()->flush();


        return $taxon;
    }

    /**
     * @return TaxonSlugGeneratorInterface|object
     */
    protected function getTaxonSlugGenerator()
    {
        return $this->getContainer()->get('sylius.generator.taxon_slug');
    }

    protected function findParentTaxonCode($left, $right)
    {
        $query = <<<EOM
select concat('forum-', old.forum_id) as code
from jedisjeux.phpbb3_forums old
where old.left_id < ?
and old.right_id > ?
and old.left_id 
order by old.left_id desc
limit 1
EOM;
        return $this->getManager()->getConnection()->fetchColumn($query, [$left, $right]);
    }

    protected function updatePrivateTaxons()
    {
        $this->getManager()->getConnection()->executeQuery(<<<EOF
        update sylius_taxon taxon
  INNER JOIN sylius_taxon_translation translation
    ON translation.translatable_id = taxon.id
  LEFT JOIN sylius_taxon child
    ON child.tree_left >= taxon.tree_left
       AND child.tree_right <= taxon.tree_right
       AND child.tree_root = taxon.tree_root
  LEFT JOIN sylius_taxon_translation childTranslation
    ON childTranslation.translatable_id = child.id
  SET child.public = 0
WHERE taxon.code LIKE 'forum%'
      AND translation.name in ('Tatooïne : La Cantina', 'Maitre Jedi')
EOF
        );
    }

    /**
     * @return array
     */
    protected function getTaxons()
    {
        $query = <<<EOM
select old.forum_id as id, 
old.forum_name as name, 
old.forum_desc as description, 
old.left_id as tree_left,
old.right_id as tree_right
from jedisjeux.phpbb3_forums old
where old.parent_id > 0
order by old.left_id
EOM;

        return $this->getManager()->getConnection()->fetchAll($query);
    }

    /**
     * @return TaxonInterface
     */
    public function getRootTaxon()
    {
        return $this->getContainer()
            ->get('sylius.repository.taxon')
            ->findOneBy(array('code' => Taxon::CODE_FORUM));
    }

    /**
     * @return Factory
     */
    protected function getFactory()
    {
        return $this->getContainer()->get('sylius.factory.taxon');
    }

    /**
     * @return TaxonRepository
     */
    protected function getRepository()
    {
        return $this->getContainer()->get('sylius.repository.taxon');
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
