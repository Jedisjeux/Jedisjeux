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
use Sylius\Component\User\Model\User as BaseUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\LegacyPasswordAuthenticatedUserInterface;

/**
 * @author Loïc Frémont <lc.fremont@gmail.com>
 */
#[ORM\Entity]
#[UniqueEntity(fields: ['username'], errorPath: 'username', message: 'app.user.username.unique', groups: ['sylius'])]
#[ORM\Table('sylius_user')]
class User extends BaseUser implements AppUserInterface, LegacyPasswordAuthenticatedUserInterface
{
    /**
     * Sylius 2 dropped the salt, but it is still needed to check the legacy sha512 passwords until they are all rehashed on login.
     *
     * @var string
     */
    #[ORM\Column(type: 'string')]
    private $salt;

    /**
     * @var CustomerInterface
     */
    #[ORM\OneToOne(targetEntity: CustomerInterface::class, inversedBy: 'user', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private $customer;

    public function __construct()
    {
        parent::__construct();

        $this->salt = base_convert(bin2hex(random_bytes(20)), 16, 36);
    }

    public function getSalt(): ?string
    {
        return $this->salt;
    }

    /**
     * The salt has to be serialized too, otherwise the security token considers the user has changed and logs them out.
     */
    public function __serialize(): array
    {
        return [...parent::__serialize(), $this->salt];
    }

    public function __unserialize(array $data): void
    {
        parent::__unserialize($data);

        $this->salt = $data[5] ?? null;
    }

    public function getCustomer(): ?CustomerInterface
    {
        return $this->customer;
    }

    public function setCustomer(?CustomerInterface $customer): void
    {
        $this->customer = $customer;
    }

    public function getEmail(): ?string
    {
        return $this->customer->getEmail();
    }

    public function setEmail(?string $email): void
    {
        $this->customer->setEmail($email);
    }

    public function getEmailCanonical(): ?string
    {
        return $this->customer->getEmailCanonical();
    }

    public function setEmailCanonical(?string $emailCanonical): void
    {
        $this->customer->setEmailCanonical($emailCanonical);
    }
}
