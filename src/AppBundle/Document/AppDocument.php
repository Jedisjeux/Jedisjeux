<?php

/**
 * This file is part of Jedisjeux
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Document;

use ONGR\ElasticsearchBundle\Annotation as ElasticSearch;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 *
 * @ElasticSearch\Document(type="app")
 */
class AppDocument
{
    public const TYPE_ARTICLE = 'article';
    public const TYPE_PRODUCT = 'product';
    public const TYPE_TOPIC = 'topic';

    /**
     * @var string
     *
     * @ElasticSearch\Property(type="keyword")
     */
    private $type;

    /**
     * @var string
     *
     * @ElasticSearch\Property(
     *    type="text",
     *    name="name",
     *    options={
     *        "fielddata"=true,
     *        "analyzer"="incrementalAnalyzer"
     *    }
     * )
     */
    private $name;

    /**
     * @var \DateTimeInterface
     *
     * @ElasticSearch\Property(type="date")
     */
    private $createdAt;

    /**
     * @var ImageDocument|null
     *
     * @ElasticSearch\Embedded(class="AppBundle:ImageDocument")
     */
    private $image;

    /**
     * @var TopicDocument|null
     *
     * @ElasticSearch\Embedded(class="AppBundle:TopicDocument")
     */
    private $topic;

    /**
     * @var ProductDocument|null
     *
     * @ElasticSearch\Embedded(class="AppBundle:ProductDocument")
     */
    private $product;

    /**
     * @var ArticleDocument|null
     *
     * @ElasticSearch\Embedded(class="AppBundle:ArticleDocument")
     */
    private $article;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return ImageDocument|null
     */
    public function getImage(): ?ImageDocument
    {
        return $this->image;
    }

    /**
     * @param ImageDocument|null $image
     */
    public function setImage(?ImageDocument $image): void
    {
        $this->image = $image;
    }

    /**
     * @return TopicDocument|null
     */
    public function getTopic(): ?TopicDocument
    {
        return $this->topic;
    }

    /**
     * @param TopicDocument|null $topic
     */
    public function setTopic(?TopicDocument $topic): void
    {
        $this->topic = $topic;
    }

    /**
     * @return ProductDocument|null
     */
    public function getProduct(): ?ProductDocument
    {
        return $this->product;
    }

    /**
     * @param ProductDocument|null $product
     */
    public function setProduct(?ProductDocument $product): void
    {
        $this->product = $product;
    }

    /**
     * @return ArticleDocument|null
     */
    public function getArticle(): ?ArticleDocument
    {
        return $this->article;
    }

    /**
     * @param ArticleDocument|null $article
     */
    public function setArticle(?ArticleDocument $article): void
    {
        $this->article = $article;
    }
}
