<?php

namespace App\Tests;
use App\Tests\AuthenticationTest;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

use function PHPSTORM_META\type;

class GameTest extends ApiTestCase
{
    public function testRoute(): void
    {
        $client = self::createClient();

        $response = $client->request('GET', '/api/games');

        $this->assertResponseStatusCodeSame(401);

        $this->assertJsonContains(['message' => 'JWT Token not found']);

        $json = $client->request('POST', '/authentication_token', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'admin@email.com',
                'password' => 'password',
            ],
        ])->toArray();

        $client->request('GET', '/api/games', ['auth_bearer' => $json['token']]);
        $this->assertResponseIsSuccessful();
        

    }
}
