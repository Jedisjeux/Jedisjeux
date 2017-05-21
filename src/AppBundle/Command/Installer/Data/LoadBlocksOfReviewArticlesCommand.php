<?php

/*
 * This file is part of jdj.
 *
 * (c) Mobizel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Entity\Article;
use AppBundle\Entity\Block;
use AppBundle\Entity\BlockImage;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadBlocksOfReviewArticlesCommand extends LoadBlocksOfArticlesCommand
{
    const BATCH_SIZE = 20;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:blocks-of-review-articles:load')
            ->setDescription('Load blocks of all review articles')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command loads blocks of all review articles.
EOT
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        $i = 0;

        foreach ($this->getReviewArticlesBlocks() as $key => $data) {

            $blocks = [
                [
                    'code' => $data['article_code'] . '-0',
                    'body' => $data['material'],
                    'image_position' => 'left',
                    'image_label' => null,
                    'image' => $data['material_image_path'],
                    'title' => 'Matériel',
                    'class' => null,
                ], [
                    'code' => $data['article_code'] . '-1',
                    'body' => $data['rules'],
                    'image_position' => 'right',
                    'image_label' => null,
                    'image' => $data['rules_image_path'],
                    'title' => 'Règles',
                    'class' => null,
                ], [
                    'code' => $data['article_code'] . '-2',
                    'body' => $data['lifetime'],
                    'image_position' => 'top',
                    'image_label' => null,
                    'image' => $data['lifetime_image_path'],
                    'title' => 'Durée de vie',
                    'class' => null,
                ]
            ];

            if (!empty($data['advice'])) {
                $blocks[] = [
                    'code' => $data['article_code'] . '-3',
                    'body' => $data['advice'],
                    'image_position' => 'top',
                    'image_label' => null,
                    'image' => null,
                    'title' => 'Le conseil de Jedisjeux',
                    'class' => 'well',
                ];
            }

            foreach ($blocks as $blockData) {

                $output->writeln(sprintf("Loading block <comment>%s</comment> of <comment>%s</comment> article", $blockData['code'], $data['article_title']));

                /** @var Article $article */
                $article = $this->getContainer()->get('app.repository.article')->find($data['article_id']);
                $block = $this->createOrReplaceBlock($blockData);
                $article
                    ->addBlock($block);

                $this->getManager()->persist($block);

            }

            if (($i % self::BATCH_SIZE) === 0) {
                $this->getManager()->flush(); // Executes all updates.
                $this->getManager()->clear(); // Detaches all objects from Doctrine!
            }

            ++$i;


        }

        $this->getManager()->flush();
        $this->getManager()->clear();

        $output->writeln(sprintf("<info>%s</info>", "Blocks of all review articles have been successfully loaded."));
    }

    /**
     * @return array
     */
    protected function getReviewArticlesBlocks()
    {
        $query = <<<EOM
SELECT
  article.id              AS article_id,
  article.title           AS article_title,
  article.code            as article_code,
  test.materiel           AS material,
  test.regle              AS rules,
  test.duree_vie          AS lifetime,
  test.conseil            AS advice,
  test.note_materiel / 2  AS materialRating,
  test.note_regle / 2     AS rulesRating,
  test.note_duree_vie / 2 AS lifetimeRating,
  material_image.img_nom  AS material_image_path,
  rules_image.img_nom     AS rules_image_path,
  lifetime_image.img_nom  AS lifetime_image_path
FROM jedisjeux.jdj_tests test
  INNER JOIN jdj_article article
    ON article.code = concat('review-article-', test.game_id)
  LEFT JOIN jedisjeux.jdj_images_elements material_img
    ON material_img.elem_type = 'test'
       AND material_img.elem_id = test.game_id
       AND material_img.ordre = 1
  LEFT JOIN jedisjeux.jdj_images material_image
    ON material_image.img_id = material_img.img_id
  LEFT JOIN jedisjeux.jdj_images_elements rules_img
    ON rules_img.elem_type = 'test'
       AND rules_img.elem_id = test.game_id
       AND rules_img.ordre = 2
  LEFT JOIN jedisjeux.jdj_images rules_image
    ON rules_image.img_id = rules_img.img_id
  LEFT JOIN jedisjeux.jdj_images_elements lifetime_img
    ON lifetime_img.elem_type = 'test'
       AND lifetime_img.elem_id = test.game_id
       AND lifetime_img.ordre = 3
  LEFT JOIN jedisjeux.jdj_images lifetime_image
    ON lifetime_image.img_id = lifetime_img.img_id
GROUP BY article.code
EOM;

        return $this->getManager()->getConnection()->fetchAll($query);
    }
}
