<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;

class AuthenticationTest extends ApiTestCase
{
    public function testLogin(): void
    {
        $client = self::createClient();
        $container = self::getContainer();

        $user = new User();
        $user->setEmail('test@test.com');
        $user->setPassword(
            $container->get('security.user_password_hasher')->hashPassword($user, 'Password123')
        );

        $manager = $container->get('doctrine')->getManager();
        $manager->persist($user);
        $manager->flush();

        $response = $client->request('POST', '/api/login_check', [
            'json' => [
                'username' => 'test@test.com',
                'password' => 'Password123',
            ],
        ]);

        $json = $response->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);

        $client->request('GET', '/api/tasks');
        $this->assertResponseStatusCodeSame(401);

        $client->request('GET', '/api/tasks', ['auth_bearer' => $json['token']]);
        $this->assertResponseIsSuccessful();
    }
}
