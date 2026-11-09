<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Customer\Model\CustomerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Resource\Metadata\ApplyStateMachineTransition;
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
#[ORM\Table(name: 'jdj_product_box')]
#[AsResource(
    alias: 'app.product_box',
    section: 'backend',
    templatesDir: '@SyliusAdminUi/crud',
    routePrefix: '/admin',
    vars: ['subheader' => 'app.ui.manage_product_boxes'],
    operations: [
        new Index(grid: 'app_backend_product_box', vars: ['icon' => 'th']),
        new Update(
            redirectToRoute: 'app_backend_product_box_update',
            vars: [
                'templates' => [
                    'toolbar' => 'backend/product_box/_toolbar.html.twig',
                    'form' => 'backend/product_box/_form.html.twig',
                ],
            ],
        ),
        new Delete(),
        new ApplyStateMachineTransition(
            path: 'product-boxes/{id}/accept',
            methods: ['PUT'],
            stateMachineGraph: 'app_product_box',
            stateMachineTransition: 'accept',
        ),
        new ApplyStateMachineTransition(
            path: 'product-boxes/{id}/reject',
            methods: ['PUT'],
            stateMachineGraph: 'app_product_box',
            stateMachineTransition: 'reject',
        ),
    ],
)]
#[AsResource(
    alias: 'app.product_box',
    section: 'frontend',
    operations: [
        new Create(
            path: '/products/{slug}/boxes/new',
            template: 'frontend/product_box/create.html.twig',
            factoryMethod: 'createForProduct',
            factoryArguments: [
                'product' => "@=throw_not_found_on_null(sylius_repositories.get('sylius.repository.product').findOneBySlug(request.getLocale(), request.attributes.get('slug')))",
            ],
            notificationMessage: 'app.product_box.wait_for_the_acceptation',
            redirectToRoute: 'sylius_frontend_product_show',
            redirectArguments: ['slug' => 'resource.product.slug'],
        ),
    ],
)]
class ProductBox implements ResourceInterface
{
    use IdentifiableTrait;
    use TimestampableTrait;
    use ToggleableTrait;

    const RATIO = 0.645;

    const STATUS_NEW = 'new';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';

    /**
     * @var string
     */
    #[ORM\Column(type: 'string')]
    private $status;

    /**
     * @var ProductInterface|null
     */
    #[ORM\ManyToOne(targetEntity: Product::class)]
    private $product;

    /**
     * @var ProductVariantInterface|null
     */
    #[ORM\ManyToOne(targetEntity: ProductVariant::class, inversedBy: 'boxes')]
    private $productVariant;

    /**
     * @var ProductBoxImage|null
     */
    #[ORM\OneToOne(targetEntity: ProductBoxImage::class, cascade: ['persist'])]
    #[Assert\Valid]
    private $image;

    /**
     * @var int
     */
    #[ORM\Column(type: 'integer')]
    private $height;

    /**
     * @var int|null
     */
    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $realHeight;

    /**
     * @var CustomerInterface|null
     */
    #[ORM\ManyToOne(targetEntity: \Sylius\Component\Customer\Model\CustomerInterface::class)]
    private $author;

    public function __construct()
    {
        $this->status = static::STATUS_NEW;
        $this->disable();
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getImage(): ?ProductBoxImage
    {
        return $this->image;
    }

    public function setImage(?ProductBoxImage $image): void
    {
        $this->image = $image;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): void
    {
        $this->height = $height;
    }

    public function getRealHeight(): ?int
    {
        return $this->realHeight;
    }

    public function setRealHeight(?int $realHeight): void
    {
        $this->realHeight = $realHeight;

        if (null === $realHeight) {
            $this->setHeight(null);
        } else {
            $height = round($realHeight * static::RATIO);
            $this->setHeight($height);
        }
    }

    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    public function setProduct(?ProductInterface $product): void
    {
        $this->product = $product;

        if (null === $product) {
            $this->setProductVariant(null);

            return;
        }

        $this->setProductVariant($product->getFirstVariant());
    }

    public function getProductVariant(): ?ProductVariantInterface
    {
        return $this->productVariant;
    }

    public function setProductVariant(?ProductVariantInterface $productVariant): void
    {
        $this->productVariant = $productVariant;
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
