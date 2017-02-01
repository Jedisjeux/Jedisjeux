<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 06/04/2016
 * Time: 09:04
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Command\LogMemoryUsageTrait;
use AppBundle\Entity\Article;
use AppBundle\Entity\Product;
use AppBundle\Entity\Taxon;
use AppBundle\Entity\Topic;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ImagineBlock;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Image;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadNewsCommand extends AbstractLoadDocumentCommand
{
    use LogMemoryUsageTrait;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:news:load')
            ->setDescription('Load news')
            ->addOption('no-update')
            ->addOption('limit', null, InputOption::VALUE_REQUIRED, 'Set limit of news to import');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        gc_collect_cycles();

        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        foreach ($this->getNews() as $key => $data) {
            $output->writeln(sprintf("Loading <comment>%s</comment> news", $data['title']));
            $this->logMemoryUsage($output);

            $article = $this->createOrReplaceArticle($data);
            $articleContent = $article->getDocument();

            $this->getDocumentManager()->persist($articleContent);
            $this->getDocumentManager()->flush();

            $blocks = [
                [
                    'id' => $data['id'],
                    'body' => $data['body'],
                    'image_position' => 'left',
                    'image_label' => null,
                    'image' => $data['mainImage'],
                    'title' => null,
                    'class' => null,
                ]
            ];
            $this->populateBlocks($articleContent, $blocks);

            $this->getDocumentManager()->flush();

            /** @var TaxonInterface $mainTaxon */
            $mainTaxon = $this->getTaxonRepository()->findOneBy(['code' => Taxon::CODE_NEWS]);
            $article->setMainTaxon($mainTaxon);

            if (null !== $data['product_id']) {
                /** @var ProductInterface $product */
                $product = $this->getContainer()->get('sylius.repository.product')->find($data['product_id']);

                $article
                    ->setProduct($product);
            }

            if (null !== $data['topic_id']) {
                /** @var Topic $topic */
                $topic = $this->getContainer()->get('app.repository.topic')->find($data['topic_id']);

                $article
                    ->setTopic($topic);
            }

            /** @var CustomerInterface $author */
            $author = $this->getContainer()->get('sylius.repository.customer')->find($data['author_id']);
            $article
                ->setAuthor($author);

            $this->getDocumentManager()->persist($articleContent);
            $this->getDocumentManager()->flush();

            $this->getManager()->persist($article);
            $this->getManager()->flush();
            $this->getManager()->clear();

            $this->getDocumentManager()->detach($articleContent);
            $this->getDocumentManager()->clear();

            if ($key > 0 and $key%10 === 0) {
                $this->clearDoctrineCache();
            }
        }

        $this->clearDoctrineCache();
        $stats = $this->getTotalOfItemsLoaded();
        $this->showTotalOfItemsLoaded($stats['itemCount'], $stats['totalCount']);
    }

    protected function getNews()
    {
        $query = <<<EOM
select      old.news_id as id,
            old.titre as title,
            concat(replace(old.titre_clean, ' ', '-'), '-n-', old.news_id) as name,
            old.date as publishedAt,
            old.text as body,
            old.photo as mainImage,
            customer.id as author_id,
            old.photo as mainImage,
            product.id as product_id,
            topic.id as topic_id,
            old.nb_clicks as view_count,
            case valid
                when 1 then 'published'
                when 5 then 'need-a-review'
                when 3 then 'ready-to-publish'
                else 'new'
            end as status
from        jedisjeux.jdj_news old
  inner join sylius_customer customer
    on customer.code = concat('user-', old.user_id)
  left join sylius_product_variant productVariant
    on productVariant.code = concat('game-', old.game_id)
  left join sylius_product product
    on product.id = productVariant.product_id
  left join jdj_topic topic
    on topic.code = concat('topic-', old.topic_id)
WHERE       old.valid >= 0
            AND       old.type_lien in (0, 1)

EOM;

        if ($this->input->getOption('no-update')) {
            $query .= <<<EOM

AND not exists (
   select 0
   from jdj_article article
   where article.code = concat('news-', old.news_id)
)
EOM;
        }

        $query .= <<<EOM
 
order by    old.date desc
EOM;

        if ($this->input->getOption('limit')) {
            $query .= sprintf(' limit %s', $this->input->getOption('limit'));
        }

        return $this->getDatabaseConnection()->fetchAll($query);
    }

    /**
     * @return array
     */
    protected function getTotalOfItemsLoaded()
    {
        $query = <<<EOM
select count(article.id) as itemCount, count(0) as totalCount
  from jedisjeux.jdj_news news
left join jdj_article article
    on article.code = concat('news-', news.news_id)
where news.type_lien in (0, 1)
EOM;

        return $this->getDatabaseConnection()->fetchAssoc($query);
    }

    /**
     * @param array $data
     *
     * @return Article
     */
    protected function createOrReplaceArticle(array $data)
    {
        $article = $this->findArticle($data['name']);

        if (null === $article) {
            $article = $this->getFactory()->createNew();
        }

        $article
            ->setCode(sprintf('news-%s', $data['id']))
            ->setStatus($data['status'])
            ->setViewCount($data['view_count']);

        $articleDocument = $article->getDocument();

        if (null === $articleDocument) {
            $articleDocument = $this->getDocumentFactory()->createNew();
            $article
                ->setDocument($articleDocument);
        }

        if (null !== $data['mainImage']) {
            $mainImage = $articleDocument->getMainImage();

            if (null === $mainImage) {
                /** @var ImagineBlock $mainImage */
                $mainImage = $this->getContainer()->get('app.factory.imagine_block')->createNew();
            }

            $image = new Image();
            $image->setFileContent(file_get_contents($this->getImageOriginalPath($data['mainImage'])));

            $mainImage
                ->setParentDocument($articleDocument)
                ->setImage($image);

            $articleDocument
                ->setMainImage($mainImage);
        }

        $articleDocument->setName($data['name']);
        $articleDocument->setTitle($data['title']);
        $articleDocument->setPublishable(Article::STATUS_PUBLISHED === $data['status']);
        $articleDocument->setPublishStartDate(\DateTime::createFromFormat('Y-m-d H:i:s', $data['publishedAt']));

        return $article;
    }
}
