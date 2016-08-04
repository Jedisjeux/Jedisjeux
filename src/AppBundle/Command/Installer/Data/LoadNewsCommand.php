<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 06/04/2016
 * Time: 09:04
 */

namespace AppBundle\Command\Installer\Data;

use AppBundle\Command\LogMemoryUsageTrait;
use AppBundle\Document\ArticleContent;
use AppBundle\Entity\Article;
use AppBundle\Entity\Taxon;
use AppBundle\Entity\Topic;
use Doctrine\ODM\PHPCR\DocumentRepository;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\User\Model\CustomerInterface;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ImagineBlock;
use Symfony\Cmf\Bundle\MediaBundle\Doctrine\Phpcr\Image;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
class LoadNewsCommand extends AbstractLoadDocumentCommand
{
    use LogMemoryUsageTrait;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('app:news:load')
            ->setDescription('Load news');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        gc_collect_cycles();
        $this->output = $output;
        $output->writeln(sprintf("<comment>%s</comment>", $this->getDescription()));

        foreach ($this->getNews() as $data) {
            $output->writeln(sprintf("Loading <info>%s</info> news", $data['title']));
            $this->logMemoryUsage($output);

            $article = $this->createOrReplaceArticle($data);
            $articleContent = $article->getDocument();

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

            $this->getDocumentManager()->persist($articleContent);
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


        }
    }

    protected function getNews()
    {
        $query = <<<EOM
select      old.news_id as id,
            old.titre as title,
            replace(old.titre_clean, ' ', '-') as name,
            old.date as publishedAt,
            old.text as body,
            old.photo as mainImage,
            customer.id as author_id,
            old.photo as mainImage,
            product.id as product_id,
            topic.id as topic_id
from        jedisjeux.jdj_news old
  inner join sylius_customer customer
    on customer.code = concat('user-', old.user_id)
  left join sylius_product_variant productVariant
    on productVariant.code = concat('game-', old.game_id)
  left join sylius_product product
    on product.id = productVariant.product_id
  left join jdj_topic topic
    on topic.id = old.topic_id
WHERE       old.valid = 1
            AND       old.type_lien in (0, 1)
order by    old.date desc
EOM;

        return $this->getDatabaseConnection()->fetchAll($query);
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

        $articleDocument = $article->getDocument();

        if (null === $articleDocument) {
            $articleDocument = $this->getDocumentFactory()->createNew();
            $article
                ->setDocument($articleDocument);
        }

        if (null !== $data['mainImage']) {
            $mainImage = $articleDocument->getMainImage();

            if (null === $mainImage) {
                $mainImage = new ImagineBlock();
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
        $articleDocument->setPublishable(true);
        $articleDocument->setPublishStartDate(\DateTime::createFromFormat('Y-m-d H:i:s', $data['publishedAt']));

        return $article;
    }
}
