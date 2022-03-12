<?php

namespace App\Tests\Controller;

use Sylius\Bundle\UserBundle\Doctrine\ORM\UserRepository;
use Sylius\Component\User\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\TestBrowserToken;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;

class ImportControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->client->followRedirects(true);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail('admin@sylius.com');
        $this->simulateLogin($user);
    }

    public function testIndex(): void
    {
        $this->client->request('GET', 'admin/import/product');
        $this->assertResponseIsSuccessful();
    }

    public function testUpload(): void
    {
        $this->client->request('GET', 'admin/import/upload/product');
        $this->assertResponseIsSuccessful();
    }

    private function simulateLogin(UserInterface $user): void
    {
        // Get session
        $session = self::getContainer()->get('session');

        // Set firewall
        $firewall = 'admin';

        // Authenticate the user
        $token = new TestBrowserToken($user->getRoles(), $user, $firewall);
        $session->set('_security_' . $firewall, serialize($token));
        $session->save();

        // Set cookie
        $cookie = new Cookie($session->getName(), $session->getId());
        $this->client->getCookieJar()->set($cookie);
    }
}
