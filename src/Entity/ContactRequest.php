<?php

/*
 * This file is part of jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Show;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
#[ORM\Entity]
#[ORM\Table('jdj_contact_request')]
#[AsResource(
    alias: 'app.contact_request',
    section: 'backend',
    templatesDir: '@SyliusAdminUi/crud',
    routePrefix: '/admin',
    vars: ['subheader' => 'app.ui.manage_contact_requests'],
    operations: [
        new Index(grid: 'app_backend_contact_request', vars: ['icon' => 'mail']),
        new Show(template: 'backend/contact_request/show.html.twig'),
        new Delete(),
    ],
)]
#[AsResource(
    alias: 'app.contact_request',
    section: 'frontend',
    operations: [
        new Create(
            path: '/contact',
            template: 'frontend/contact_request/create.html.twig',
            redirectToRoute: 'app_frontend_contact_request_create',
            redirectArguments: ['id' => '@=null'],
        ),
    ],
)]
class ContactRequest implements ResourceInterface
{
    use IdentifiableTrait;
    use TimestampableTrait;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string')]
    protected $firstName;

    /**
     * @var string
     *
     * use Prophecy\Argument;
     */
    #[ORM\Column(type: 'string')]
    protected $lastName;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string')]
    protected $email;

    /**
     * @var string
     */
    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    protected $body;

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getFullName(): string
    {
        return trim(sprintf('%s %s', $this->firstName, $this->lastName));
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): void
    {
        $this->body = $body;
    }
}
