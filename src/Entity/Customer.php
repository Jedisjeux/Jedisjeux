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

use App\Form\Type\Customer\CustomerProfileType;
use App\Form\Type\Customer\CustomerSimpleRegistrationType;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Sylius\Component\Customer\Model\Customer as BaseCustomer;
use Sylius\Component\Review\Model\ReviewerInterface;
use Sylius\Component\User\Model\UserInterface;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Metadata\Update;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Loïc Frémont <loic@mobizel.com>
 */
#[ORM\Entity]
#[JMS\ExclusionPolicy('all')]
#[ORM\Table(name: 'sylius_customer')]
#[AsResource(
    alias: 'sylius.customer',
    section: 'backend',
    templatesDir: 'backend/crud',
    routePrefix: '/admin',
    vars: [
        'subheader' => 'sylius.ui.manage_your_customers',
        'templates' => ['form' => 'backend/customer/_form.html.twig'],
    ],
    operations: [
        new Index(grid: 'sylius_backend_customer', vars: ['icon' => 'users']),
        new Create(redirectToRoute: 'sylius_backend_customer_index'),
        new Update(redirectToRoute: 'sylius_backend_customer_index'),
        new Show(template: 'backend/customer/show.html.twig'),
    ],
)]
#[AsResource(
    alias: 'sylius.customer',
    section: 'frontend',
    operations: [
        new Create(
            shortName: 'register',
            routeName: 'app_frontend_register',
            path: '/register',
            template: 'frontend/register.html.twig',
            formType: CustomerSimpleRegistrationType::class,
            eventShortName: 'register',
            notificationMessage: 'sylius.customer.register',
            redirectToRoute: 'app_homepage',
            redirectArguments: ['id' => '@=null'],
        ),
        new Show(
            shortName: 'account_dashboard',
            routeName: 'sylius_frontend_account_dashboard',
            path: '/mon-compte/accueil',
            template: 'frontend/account/dashboard.html.twig',
            repositoryMethod: 'find',
            repositoryArguments: ['id' => '@=user ? user.getCustomer().getId() : null'],
        ),
        new Update(
            shortName: 'account_profile_update',
            eventShortName: 'update',
            routeName: 'sylius_frontend_account_profile_update',
            path: '/mon-compte/profile/edit',
            template: 'frontend/account/updateProfile.html.twig',
            formType: CustomerProfileType::class,
            repositoryMethod: 'find',
            repositoryArguments: ['id' => '@=user ? user.getCustomer().getId() : null'],
            notificationMessage: 'sylius.customer.update_profile',
            redirectToRoute: 'sylius_frontend_account_dashboard',
            redirectArguments: ['id' => '@=null'],
        ),
        new Show(
            shortName: 'user_show',
            routeName: 'sylius_frontend_user_show',
            path: '/utilisateurs/{username}/',
            template: 'frontend/user/show.html.twig',
            repositoryMethod: 'findOneByUsername',
            repositoryArguments: ['username' => "@=request.attributes.get('username')"],
        ),
    ],
)]
class Customer extends BaseCustomer implements CustomerInterface, ReviewerInterface
{
    /**
     * @var UserInterface
     */
    #[ORM\OneToOne(targetEntity: \Sylius\Component\User\Model\UserInterface::class, mappedBy: 'customer', cascade: ['persist'])]
    #[Assert\Valid(groups: ['sylius'])]
    private $user;

    /**
     * @var Avatar
     */
    #[ORM\ManyToOne(targetEntity: \App\Entity\Avatar::class, cascade: ['persist'])]
    #[JMS\Expose]
    private $avatar;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', nullable: true, unique: true)]
    private $code;

    public function getAvatar(): ?Avatar
    {
        return $this->avatar;
    }

    /**
     * @param Avatar $avatar
     */
    public function setAvatar(?Avatar $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        if ($this->user === $user) {
            return;
        }

        \Webmozart\Assert\Assert::nullOrIsInstanceOf($user, AppUserInterface::class);

        $previousUser = $this->user;
        $this->user = $user;

        if ($previousUser instanceof AppUserInterface) {
            $previousUser->setCustomer(null);
        }

        if ($user instanceof AppUserInterface) {
            $user->setCustomer($this);
        }
    }

    public function __toString(): string
    {
        // A customer created from the admin has an account without username
        return $this->user?->getUsername() ?? parent::__toString();
    }
}
