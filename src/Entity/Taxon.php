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

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Sylius\Component\Taxonomy\Model\Taxon as BaseTaxon;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Update;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
#[ORM\Entity]
#[ORM\Table(name: 'sylius_taxon')]
#[AsResource(
    alias: 'sylius.taxon',
    section: 'backend',
    templatesDir: '@SyliusAdminUi/crud',
    routePrefix: '/admin/taxons',
    vars: ['templates' => ['form' => 'backend/taxon/_form.html.twig']],
    operations: [
        new Index(path: '/', grid: 'sylius_backend_root_taxon'),
        new Index(
            shortName: 'index_by_parent',
            path: 'children/{id}/',
            template: '@SyliusAdminUi/crud/index.html.twig',
            grid: 'sylius_backend_taxon_from_root',
        ),
        new Create(
            shortName: 'create_for_parent',
            eventShortName: 'create',
            path: 'new/{id}',
            template: '@SyliusAdminUi/crud/create.html.twig',
            factoryMethod: 'createForParent',
            factoryArguments: [
                'parent' => "@=throw_not_found_on_null(sylius_repositories.get('sylius.repository.taxon').find(request.attributes.get('id')))",
            ],
            redirectToRoute: 'sylius_backend_taxon_index',
            vars: ['subheader' => 'sylius.ui.manage_categorization_of_your_products'],
        ),
        new Create(path: 'roots/new', redirectToRoute: 'sylius_backend_taxon_index'),
        new Update(path: '{id}/edit', redirectToRoute: 'sylius_backend_taxon_index'),
        new Delete(path: '{id}', redirectToRoute: 'sylius_backend_taxon_index'),
    ],
)]
class Taxon extends BaseTaxon
{
    // taxons of articles
    const CODE_ARTICLE = 'articles';
    const CODE_INTERVIEW = 'interviews';
    const CODE_IN_THE_BOXES = 'in-the-boxes';
    const CODE_NEWS = 'news';
    const CODE_PREVIEWS = 'previews';
    const CODE_REPORT_ARTICLE = 'report-articles';
    const CODE_REVIEW_ARTICLE = 'review-articles';
    const CODE_VIDEO = 'videos';

    // taxons of forum
    const CODE_FORUM = 'forum';

    // taxons of products
    const CODE_MECHANISM = 'mechanisms';
    const CODE_THEME = 'themes';
    const CODE_TARGET_AUDIENCE = 'target-audience';
    const CODE_CHILD = 'target-audiences-1';
    const CODE_BEGINNER = 'target-audiences-2';
    const CODE_ADVANCED_USER = 'target-audiences-3';
    const CODE_EXPERT = 'target-audiences-4';

    // taxons of people
    const CODE_ZONE = 'zones';

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private $iconClass;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', nullable: true)]
    private $color;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean')]
    private $public;

    /**
     * @var int
     */
    #[ORM\Column(type: 'integer')]
    private $topicCount;

    /**
     * @var int
     */
    #[ORM\Column(type: 'integer')]
    private $productCount;

    /**
     * Taxon constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->topicCount = 0;
        $this->productCount = 0;
        $this->public = true;
    }

    /**
     * @return string
     */
    #[JMS\VirtualProperty]
    #[JMS\Groups(['Default', 'Autocomplete'])]
    public function getCode(): ?string
    {
        return parent::getCode();
    }

    /**
     * @return string
     */
    #[JMS\VirtualProperty]
    #[JMS\Groups(['Detailed', 'Autocomplete'])]
    public function getName(): ?string
    {
        return parent::getName();
    }

    public function getIconClass(): ?string
    {
        return $this->iconClass;
    }

    public function setIconClass(?string $iconClass): void
    {
        $this->iconClass = $iconClass;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): void
    {
        $this->color = $color;
    }

    public function isPublic(): bool
    {
        return $this->public;
    }

    public function setPublic(bool $public): void
    {
        $this->public = $public;
    }

    public function getTopicCount(): int
    {
        return $this->topicCount;
    }

    public function setTopicCount(int $topicCount): void
    {
        $this->topicCount = $topicCount;
    }

    public function getProductCount(): int
    {
        return $this->productCount;
    }

    public function setProductCount(int $productCount): void
    {
        $this->productCount = $productCount;
    }
}
