<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class AuthenticationTest extends ApiTestCase
{
    
    public function testLogin(): void
    {

        $client = self::createClient();

        // retrieve a token
        $response = $client->request('POST', '/authentication_token', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'admin@email.com',
                'password' => 'password',
            ],
        ]);

        $json = $response->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);

        // test not authorized
        $client->request('GET', '/api/users');
        $this->assertResponseStatusCodeSame(401);

        // test authorized
        $client->request('GET', '/api/games', ['auth_bearer' => $json['token']]);
        $this->assertResponseIsSuccessful();
    }
}