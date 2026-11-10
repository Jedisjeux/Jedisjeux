<?php

/*
 * This file is part of Jedisjeux.
 *
 * (c) Loïc Frémont
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\Unit\Security\OAuth;

use App\Entity\Customer;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\OAuth\OAuthAccountAlreadyLinkedException;
use App\Security\OAuth\OAuthAccountManager;
use App\Security\OAuth\OAuthProfile;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Component\User\Model\UserOAuth;
use Sylius\Component\User\Model\UserOAuthInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Factory\FactoryInterface;

final class OAuthAccountManagerTest extends TestCase
{
    /** @var RepositoryInterface<UserOAuthInterface>&MockObject */
    private RepositoryInterface&MockObject $oauthRepository;

    private UserRepository&MockObject $userRepository;

    /** @var RepositoryInterface<Customer>&MockObject */
    private RepositoryInterface&MockObject $customerRepository;

    private EntityManagerInterface&MockObject $entityManager;

    private OAuthAccountManager $manager;

    protected function setUp(): void
    {
        $this->oauthRepository = $this->createMock(RepositoryInterface::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->customerRepository = $this->createMock(RepositoryInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $oauthFactory = $this->createMock(FactoryInterface::class);
        $oauthFactory->method('createNew')->willReturnCallback(static fn () => new UserOAuth());
        $customerFactory = $this->createMock(FactoryInterface::class);
        $customerFactory->method('createNew')->willReturnCallback(static fn () => new Customer());
        $userFactory = $this->createMock(FactoryInterface::class);
        $userFactory->method('createNew')->willReturnCallback(static fn () => new User());

        $this->manager = new OAuthAccountManager(
            $this->oauthRepository,
            $oauthFactory,
            $this->userRepository,
            $this->customerRepository,
            $customerFactory,
            $userFactory,
            $this->entityManager,
        );
    }

    public function testItFindsTheUserLinkedToTheProviderAccount(): void
    {
        $user = new User();
        $oauthAccount = new UserOAuth();
        $user->addOAuthAccount($oauthAccount);

        $this->oauthRepository->method('findOneBy')
            ->with(['provider' => 'github', 'identifier' => '42'])
            ->willReturn($oauthAccount);
        $this->userRepository->expects($this->never())->method('findOneByEmail');

        $this->assertSame($user, $this->manager->findUser($this->createProfile()));
    }

    public function testItLinksTheAccountOwningTheVerifiedEmail(): void
    {
        $user = new User();
        $user->setEmailVerificationToken('token');

        $this->userRepository->method('findOneByEmail')->with('player@example.com')->willReturn($user);
        $this->entityManager->expects($this->once())->method('flush');

        $this->assertSame($user, $this->manager->findUser($this->createProfile()));
        $this->assertSame('42', $user->getOAuthAccount('github')?->getIdentifier());
        // Proving the email ownership verifies the account, as the verification link does
        $this->assertTrue($user->isVerified());
        $this->assertTrue($user->isEnabled());
        $this->assertNull($user->getEmailVerificationToken());
    }

    public function testItKeepsADisabledVerifiedAccountDisabled(): void
    {
        $user = new User();
        $user->setVerifiedAt(new \DateTime('-1 year'));
        $user->setEnabled(false);

        $this->userRepository->method('findOneByEmail')->willReturn($user);

        $this->manager->findUser($this->createProfile());

        $this->assertFalse($user->isEnabled());
    }

    public function testItDoesNotMatchAnUnverifiedEmail(): void
    {
        $this->userRepository->expects($this->never())->method('findOneByEmail');
        $this->entityManager->expects($this->never())->method('flush');

        $this->assertNull($this->manager->findUser($this->createProfile(emailVerified: false)));
    }

    public function testItDoesNotLinkASecondAccountOfTheSameProvider(): void
    {
        $user = new User();
        $otherOAuthAccount = new UserOAuth();
        $otherOAuthAccount->setProvider('github');
        $otherOAuthAccount->setIdentifier('7');
        $user->addOAuthAccount($otherOAuthAccount);

        $this->userRepository->method('findOneByEmail')->willReturn($user);
        $this->entityManager->expects($this->never())->method('flush');

        $this->assertNull($this->manager->findUser($this->createProfile()));
    }

    public function testItLinksTheProviderAccountToTheSignedInUser(): void
    {
        $user = new User();

        $this->entityManager->expects($this->once())->method('flush');

        $this->manager->link($user, $this->createProfile());

        $this->assertSame('42', $user->getOAuthAccount('github')?->getIdentifier());
    }

    public function testItRefusesToLinkAProviderAccountOfAnotherUser(): void
    {
        $otherUser = new User();
        $oauthAccount = new UserOAuth();
        $otherUser->addOAuthAccount($oauthAccount);

        $this->oauthRepository->method('findOneBy')->willReturn($oauthAccount);

        $this->expectException(OAuthAccountAlreadyLinkedException::class);

        $this->manager->link(new User(), $this->createProfile());
    }

    public function testItCreatesAnEnabledUserWithTheVerifiedEmail(): void
    {
        $customer = $this->manager->createUser($this->createProfile(), 'meeple', 'Player@example.com');
        $user = $customer->getUser();

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame('Player@example.com', $customer->getEmail());
        $this->assertSame('meeple', $user->getUsername());
        $this->assertNull($user->getPassword());
        $this->assertTrue($user->isEnabled());
        $this->assertTrue($user->isVerified());
        $this->assertSame('42', $user->getOAuthAccount('github')?->getIdentifier());
    }

    public function testItCreatesADisabledUserWhenTheEmailIsNotTheVerifiedOne(): void
    {
        $user = $this->manager->createUser($this->createProfile(), 'meeple', 'other@example.com')->getUser();

        $this->assertInstanceOf(User::class, $user);
        $this->assertFalse($user->isEnabled());
        $this->assertFalse($user->isVerified());
    }

    public function testItAttachesTheNewUserToTheCustomerWithoutAccount(): void
    {
        $customer = new Customer();
        $this->customerRepository->method('findOneBy')->with(['emailCanonical' => 'player@example.com'])->willReturn($customer);

        $this->assertFalse($this->manager->isEmailTaken('Player@example.com'));
        $this->assertSame($customer, $this->manager->createUser($this->createProfile(), 'meeple', 'Player@example.com'));
    }

    public function testItKeepsTheLastProviderAccountOfAUserWithoutPassword(): void
    {
        $user = new User();
        $user->addOAuthAccount(new UserOAuth());

        $this->assertFalse($this->manager->canUnlink($user));

        $user->setPassword('hash');

        $this->assertTrue($this->manager->canUnlink($user));
    }

    private function createProfile(bool $emailVerified = true): OAuthProfile
    {
        return new OAuthProfile('github', '42', 'player@example.com', $emailVerified, 'meeple');
    }
}
