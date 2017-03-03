<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 31/01/16
 * Time: 18:58
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Command\LogMemoryUsageTrait;
use AppBundle\Document\BlockquoteBlock;
use AppBundle\Document\ArticleContent;
use AppBundle\Entity\Article;
use AppBundle\Entity\Taxon;
use AppBundle\Entity\Topic;
use Doctrine\ORM\EntityManager;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ImagineBlock;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SlideshowBlock;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Image;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LoadNewArticlesCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:new-articles:load')
            ->setDescription('Load articles');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        foreach ($this->getArticles() as $key => $data) {
            $output->writeln(sprintf("Loading <comment>%s</comment> article", $data['title']));
        }
    }

    /**
     * @return array
     */
    protected function getArticles()
    {
        $query = <<<EOM
select article.article_id as id,
       concat(replace(article.titre_clean, ' ', '-'), '-a-', article.article_id) as name,
       article.titre as title,
       article.date as publishedAt,
       article.intro as introduction,
       article.photo as mainImage,
       case article.type_article
            when 'article' then null
            when 'reportage' then 'report-articles'
            when 'interview' then 'interviews'
            when 'cdlb' then 'in-the-boxes'
            when 'preview' then 'previews'
            else null
       end as mainTaxon,     
       article.valid as publishable,
       product.id as product_id,
       topic.id as topic_id,
       user.customer_id as author_id,
       article_view.view_count
from jedisjeux.jdj_article article
  inner join jedisjeux.jdj_v_article_view_count as article_view  
    on article_view.id = article.article_id
  left join sylius_product_variant productVariant
    on productVariant.code = concat('game-', article.game_id)
  left join sylius_product product
    on product.id = productVariant.product_id
  left join jdj_topic topic
    on topic.code = concat('topic-', article.topic_id)
  left join sylius_user user
    on convert(user.username USING UTF8) = convert(article.auteur USING UTF8)
where titre_clean != ''
order by    article.date desc
EOM;

        return $this->getManager()->getConnection()->fetchAll($query);
    }

    /**
     * @return EntityManager|object
     */
    protected function getManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}