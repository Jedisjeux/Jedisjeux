<?php

/**
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Twig\Context\TopicContextFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Update;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
#[ORM\Entity]
#[ORM\Table('jdj_post')]
#[AsResource(
    alias: 'app.post',
    section: 'backend',
    templatesDir: '@SyliusAdminUi/crud',
    routePrefix: '/admin',
    vars: [
        'subheader' => 'app.ui.manage_posts',
        'templates' => ['form' => 'backend/post/_form.html.twig'],
    ],
    operations: [
        new Index(grid: 'app_backend_post', vars: ['icon' => 'comments']),
        new Update(redirectToRoute: 'app_backend_post_update'),
        new Delete(),
    ],
)]
#[AsResource(
    alias: 'app.post',
    section: 'frontend',
    operations: [
        new Index(
            shortName: 'index_by_topic',
            path: '/topics/{topicId}/posts/',
            template: 'frontend/post/index.html.twig',
            repositoryMethod: 'createPaginator',
            repositoryArguments: [
                'criteria' => "@={'topic': request.attributes.get('topicId')}",
                'sorting' => "@={'createdAt': 'asc'}",
            ],
            twigContextFactory: TopicContextFactory::class,
        ),
        new Create(
            shortName: 'create_by_topic',
            eventShortName: 'create',
            path: '/topics/{topicId}/posts/new',
            template: 'frontend/post/create.html.twig',
            factoryMethod: 'createForTopic',
            factoryArguments: [
                'topic' => "@=throw_not_found_on_null(sylius_repositories.get('app.repository.topic').find(request.attributes.get('topicId')))",
            ],
            redirectToRoute: 'app_frontend_post_index_by_topic',
            redirectArguments: [
                'topicId' => 'resource.topic.id',
                'page' => 'resource.topic.lastPageNumber',
            ],
        ),
        new Update(
            path: '/posts/{id}/edit',
            template: 'frontend/post/update.html.twig',
            repositoryMethod: 'findOneBy',
            repositoryArguments: [
                'criteria' => "@={'id': request.attributes.get('id'), 'author': user ? user.getCustomer() : null}",
            ],
            redirectToRoute: 'app_frontend_post_index_by_topic',
            redirectArguments: ['topicId' => 'resource.topic.id'],
        ),
        new Delete(
            path: '/posts/{id}',
            methods: ['DELETE'],
            repositoryMethod: 'findOneBy',
            repositoryArguments: [
                'criteria' => "@={'id': request.attributes.get('id'), 'author': user ? user.getCustomer() : null}",
            ],
            redirectTo: 'referer',
        ),
        new Create(
            shortName: 'create_for_article',
            eventShortName: 'create',
            routeName: 'app_frontend_article_post_create',
            path: '/article/{articleSlug}/posts/new',
            template: 'frontend/post/createForArticle.html.twig',
            factoryMethod: 'createForArticle',
            factoryArguments: [
                'article' => "@=throw_not_found_on_null(sylius_repositories.get('app.repository.article').findOneBySlug(request.attributes.get('articleSlug')))",
            ],
            redirectToRoute: 'app_frontend_article_show',
            redirectArguments: ['slug' => 'resource.article.slug'],
        ),
        new Update(
            shortName: 'update_for_article',
            eventShortName: 'update',
            routeName: 'app_frontend_article_post_update',
            path: '/article/{articleSlug}/posts/{id}/edit',
            template: 'frontend/post/updateForArticle.html.twig',
            repositoryMethod: 'findOneBy',
            repositoryArguments: [
                'criteria' => "@={'id': request.attributes.get('id'), 'author': user ? user.getCustomer() : null}",
            ],
            redirectToRoute: 'app_frontend_article_show',
            redirectArguments: ['slug' => 'resource.article.slug'],
        ),
        new Create(
            shortName: 'create_for_game_play',
            eventShortName: 'create',
            routeName: 'app_frontend_game_play_post_create',
            path: '/game-plays/{gamePlayId}/posts/new',
            template: 'frontend/post/createForGamePlay.html.twig',
            factoryMethod: 'createForGamePlay',
            factoryArguments: [
                'gamePlay' => "@=throw_not_found_on_null(sylius_repositories.get('app.repository.game_play').find(request.attributes.get('gamePlayId')))",
            ],
            redirectToRoute: 'app_frontend_game_play_show',
            redirectArguments: [
                'id' => 'resource.gamePlay.id',
                'productSlug' => 'resource.gamePlay.product.slug',
            ],
        ),
        new Update(
            shortName: 'update_for_game_play',
            eventShortName: 'update',
            routeName: 'app_frontend_game_play_post_update',
            path: '/game-plays/{gamePlayId}/posts/{id}/edit',
            template: 'frontend/post/updateForGamePlay.html.twig',
            repositoryMethod: 'findOneBy',
            repositoryArguments: [
                'criteria' => "@={'id': request.attributes.get('id'), 'author': user ? user.getCustomer() : null}",
            ],
            redirectToRoute: 'app_frontend_game_play_show',
            redirectArguments: [
                'id' => 'resource.gamePlay.id',
                'productSlug' => 'resource.gamePlay.product.slug',
            ],
        ),
    ],
)]
class Post implements ResourceInterface
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', nullable: true, unique: true)]
    protected $code;

    /**
     * @var string
     */
    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    protected $body;

    /**
     * @var Topic
     */
    #[ORM\OneToOne(targetEntity: Topic::class, mappedBy: 'mainPost')]
    protected $parent;

    /**
     * @var Topic
     */
    #[ORM\ManyToOne(targetEntity: Topic::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    protected $topic;

    /**
     * @var GamePlay
     */
    #[ORM\ManyToOne(targetEntity: GamePlay::class)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    protected $gamePlay;

    /**
     * @var Article
     */
    #[ORM\ManyToOne(targetEntity: Article::class)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    protected $article;

    /**
     * @var CustomerInterface
     */
    #[ORM\ManyToOne(targetEntity: \Sylius\Component\Customer\Model\CustomerInterface::class)]
    protected $author;

    /**
     * @var Post
     */
    #[ORM\ManyToOne(targetEntity: \App\Entity\Post::class, inversedBy: 'replies')]
    protected $replyTo;

    /**
     * @var ArrayCollection
     */
    #[ORM\OneToMany(targetEntity: \App\Entity\Post::class, mappedBy: 'replyTo')]
    protected $replies;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        $this->replies = new ArrayCollection();
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): void
    {
        $this->body = $body;
    }

    public function getParent(): ?Topic
    {
        return $this->parent;
    }

    public function setParent(?Topic $parent): void
    {
        $this->parent = $parent;
    }

    public function getTopic(): ?Topic
    {
        return $this->topic;
    }

    public function setTopic(?Topic $topic): void
    {
        $this->topic = $topic;

        if (null !== $topic && !$topic->hasPost($this)) {
            $topic->addPost($this);
        }
    }

    public function getGamePlay(): ?GamePlay
    {
        return $this->gamePlay;
    }

    public function setGamePlay(?GamePlay $gamePlay): void
    {
        $this->gamePlay = $gamePlay;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): void
    {
        $this->article = $article;
    }

    public function getAuthor(): ?CustomerInterface
    {
        return $this->author;
    }

    public function setAuthor(?CustomerInterface $author): void
    {
        $this->author = $author;
    }
}
