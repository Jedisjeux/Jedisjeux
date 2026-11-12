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

namespace App\Tests\Controller;

use ApiTestCase\JsonApiTestCase;
use App\Entity\User;
use App\Security\OAuth\OAuthProfile;
use App\Security\OAuth\OAuthProfileFetcher;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class OAuthControllerTest extends JsonApiTestCase
{
    #[Test]
    public function it_offers_the_configured_providers_only(): void
    {
        $crawler = $this->client->request('GET', '/login');

        $this->assertResponseCode($this->client->getResponse(), Response::HTTP_OK);
        self::assertCount(1, $crawler->filter('a[href="/connect/github"]'));
        self::assertCount(0, $crawler->filter('a[href="/connect/google"]'));

        $this->client->request('GET', '/connect/google');

        $this->assertResponseCode($this->client->getResponse(), Response::HTTP_NOT_FOUND);
    }

    #[Test]
    public function it_sends_the_visitor_to_the_provider(): void
    {
        $this->client->request('GET', '/connect/github');

        $location = (string) $this->client->getResponse()->headers->get('Location');
        self::assertStringStartsWith('https://github.com/login/oauth/authorize?', $location);
        self::assertStringContainsString('client_id=test-client-id', $location);
        self::assertStringContainsString('state=', $location);
    }

    #[Test]
    public function it_signs_in_the_account_owning_the_verified_email(): void
    {
        $this->loadFixturesFromFile('resources/oauth_users.yml');
        $this->fetchProfile(new OAuthProfile('github', '42', 'oliver@doe.com', true, 'olly'));

        $this->client->request('GET', '/connect/github/check?code=code&state=state');

        self::assertSame('/', $this->client->getResponse()->headers->get('Location'));
        self::assertSame('oliver', $this->getSignedInUsername());
        self::assertSame('42', $this->findUser('Oliver@doe.com')?->getOAuthAccount('github')?->getIdentifier());
    }

    #[Test]
    public function it_asks_a_new_member_for_a_username(): void
    {
        $this->loadFixturesFromFile('resources/oauth_users.yml');
        $this->fetchProfile(new OAuthProfile('github', '42', 'kara@example.com', true, 'Oliver'));

        $this->client->request('GET', '/connect/github/check?code=code&state=state');
        self::assertSame('/connect/register', $this->client->getResponse()->headers->get('Location'));

        $crawler = $this->client->followRedirect();
        self::assertSame('Oliver', $crawler->filter('#o_auth_registration_username')->attr('value'));

        // The provider nickname is already taken
        $this->client->submit($crawler->filter('form.form-horizontal')->form());
        $this->assertResponseCode($this->client->getResponse(), Response::HTTP_UNPROCESSABLE_ENTITY);
        self::assertNull($this->findUser('kara@example.com'));

        $this->client->submitForm('Create an account', [
            'o_auth_registration[username]' => 'Kara',
        ]);

        self::assertSame('/', $this->client->getResponse()->headers->get('Location'));
        $user = $this->findUser('kara@example.com');
        self::assertNotNull($user);
        self::assertTrue($user->isEnabled());
        self::assertNull($user->getPassword());
        self::assertSame('kara', $this->getSignedInUsername());
    }

    #[Test]
    public function it_does_not_attach_a_new_member_to_an_account_with_an_unverified_email(): void
    {
        $this->loadFixturesFromFile('resources/oauth_users.yml');
        $this->fetchProfile(new OAuthProfile('facebook', '42', 'oliver@doe.com', false, 'Olly'));

        $this->client->request('GET', '/connect/facebook/check?code=code&state=state');
        $crawler = $this->client->followRedirect();

        $this->client->submit($crawler->filter('form.form-horizontal')->form());

        $this->assertResponseCode($this->client->getResponse(), Response::HTTP_UNPROCESSABLE_ENTITY);
        self::assertStringContainsString('An account already uses this address', (string) $this->client->getResponse()->getContent());
        self::assertNull($this->findUser('oliver@doe.com')?->getOAuthAccount('facebook'));
        self::assertNull($this->getSignedInUsername());
    }

    private function fetchProfile(OAuthProfile $profile): void
    {
        $fetcher = $this->createMock(OAuthProfileFetcher::class);
        $fetcher->method('fetch')->willReturn($profile);

        // Keeps the stub for the whole scenario
        $this->client->disableReboot();
        static::getContainer()->set(OAuthProfileFetcher::class, $fetcher);
    }

    private function findUser(string $email): ?User
    {
        $this->getEntityManager()->clear();
        $user = static::getContainer()->get('sylius.repository.app_user')->findOneByEmail(strtolower($email));

        return $user instanceof User ? $user : null;
    }

    private function getSignedInUsername(): ?string
    {
        // The account pages need a signed-in member, who the token storage then holds
        $this->client->request('GET', '/mon-compte/connexions');
        if (Response::HTTP_OK !== $this->client->getResponse()->getStatusCode()) {
            return null;
        }

        $token = static::getContainer()->get('security.token_storage')->getToken();

        return $token instanceof TokenInterface ? $token->getUserIdentifier() : null;
    }
}
