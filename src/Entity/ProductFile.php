<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Resource\Metadata\ApplyStateMachineTransition;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Update;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Attribute as Vich;

#[ORM\Entity]
#[Vich\Uploadable]
#[ORM\Table(name: 'jdj_product_file')]
#[AsResource(
    alias: 'app.product_file',
    section: 'backend',
    templatesDir: '@SyliusAdminUi/crud',
    routePrefix: '/admin',
    vars: ['subheader' => 'app.ui.manage_product_files'],
    operations: [
        new Index(grid: 'app_backend_product_file', vars: ['icon' => 'th']),
        new Update(
            redirectToRoute: 'app_backend_product_file_update',
            vars: [
                'templates' => [
                    'toolbar' => 'backend/product_file/_toolbar.html.twig',
                    'form' => 'backend/product_file/_form.html.twig',
                ],
            ],
        ),
        new Delete(),
        new ApplyStateMachineTransition(
            path: 'product-files/{id}/accept',
            methods: ['PUT'],
            stateMachineGraph: 'app_product_file',
            stateMachineTransition: 'accept',
        ),
        new ApplyStateMachineTransition(
            path: 'product-files/{id}/reject',
            methods: ['PUT'],
            stateMachineGraph: 'app_product_file',
            stateMachineTransition: 'reject',
        ),
    ],
)]
#[AsResource(
    alias: 'app.product_file',
    section: 'frontend',
    operations: [
        new Index(
            path: '/products/{slug}/files/',
            template: 'frontend/product_file/index.html.twig',
            grid: 'app_frontend_product_file',
        ),
        new Create(
            path: '/products/{slug}/files/new',
            template: 'frontend/product_file/create.html.twig',
            factoryMethod: 'createForProduct',
            factoryArguments: [
                'product' => "@=throw_not_found_on_null(sylius_repositories.get('sylius.repository.product').findOneBySlug(request.getLocale(), request.attributes.get('slug')))",
            ],
            notificationMessage: 'app.product_file.wait_for_the_acceptation',
            redirectToRoute: 'sylius_frontend_product_show',
            redirectArguments: ['slug' => 'resource.product.slug'],
        ),
    ],
)]
class ProductFile extends File implements ResourceInterface
{
    const STATUS_NEW = 'new';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';

    /**
     * {@inheritdoc}
     */
    #[Vich\UploadableField(mapping: 'product_file', fileNameProperty: 'path')]
    #[Assert\File(maxSize: 6000000, mimeTypes: ['image/*', 'application/pdf'])]
    protected $file;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', unique: true)]
    private $code;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    private $title;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string')]
    private $status;

    /**
     * @var ProductInterface|null
     */
    #[ORM\ManyToOne(targetEntity: \Sylius\Component\Product\Model\ProductInterface::class, inversedBy: 'files')]
    private $product;

    /**
     * @var CustomerInterface|null
     */
    #[ORM\ManyToOne(targetEntity: \Sylius\Component\Customer\Model\CustomerInterface::class)]
    private $author;

    public function __construct()
    {
        $this->code = uniqid('file_');
        $this->status = static::STATUS_NEW;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getProduct(): ?ProductInterface
    {
        return $this->product;
    }

    public function setProduct(?ProductInterface $product): void
    {
        $this->product = $product;
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
