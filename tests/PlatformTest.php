<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Platform;
use App\Entity\PlatformBaseContent;
use Exception;

class PlatformTest extends ApiTestCase
{
    public static $adminToken;
    public static $userToken;

    public static function setUpBeforeClass(): void
    {
        self::$adminToken = self::createClient()->request('POST', '/authentication_token', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'admin@email.com',
                'password' => 'password',
            ],
        ])->toArray()['token'];

        self::$userToken = self::createClient()->request('POST', '/authentication_token', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'user0@email.com',
                'password' => 'password',
            ],
        ])->toArray()['token'];
    }

    public function testRoute(): void
    {
        $client = self::createClient();

        // assert response return 401 when no JWT Token provided
        $client->request('GET', '/api/platforms');
        $this->assertResponseStatusCodeSame(401);
        $this->assertJsonContains(['message' => 'JWT Token not found']);

        // assert response for USER (and ADMIN) with token
        $client->request('GET', '/api/platforms', ['auth_bearer' => self::$userToken]);
        $this->assertResponseIsSuccessful();
    }

    public function testGetPlatform(): void
    {
        $client = self::createClient();

        // assert response for USER (and ADMIN) with token
        $response = $client->request('GET', 'api/platforms/1', ['auth_bearer' => self::$userToken]);
        $this->assertResponseIsSuccessful();

        $platform = json_decode($response->getContent());
        $this->assertSame($platform->name, "Platform");
        $this->assertSame($platform->platformBaseContent->physicalSupport->name, "Other");
        $this->assertSame($platform->platformBaseContent->physicalContainer->name, "Other");
        $this->assertSame($platform->platformBaseContent->physicalContent[0]->name, "Book");
    }

    public function testPostPlatform(): void
    {
        $client = self::createClient();

        $platformBaseContentIri = $this->findIriBy(PlatformBaseContent::class, ['id' => 1]);

        //assert bad response w/ user
        try {
            $client->request('POST', 'api/platforms', [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [],
                'auth_bearer' => self::$userToken
            ])->getContent();
        } catch (Exception $e) {
            $this->assertStringContainsString('Réservé aux ADMINs !', $e->getMessage());
            $this->assertEquals(403, $e->getCode());
        }

        //assert too short name
        try {
            $client->request('POST', 'api/platforms', [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    "name" => "a",
                    "platformBaseContent" => $platformBaseContentIri

                ],
                'auth_bearer' => self::$adminToken
            ])->getContent();
        } catch (Exception $e) {
            $this->assertEquals(422, $e->getCode());
            $this->assertStringContainsString("caractères minimum !", $e->getMessage());
        }

        //assert good response w/ ADMIN
        $response = $client->request('POST', 'api/platforms', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                "name" => "Test Platform",
                "platformBaseContent" => $platformBaseContentIri

            ],
            'auth_bearer' => self::$adminToken
        ]);
        $this->assertResponseIsSuccessful();

        $platform = json_decode($response->getContent());

        $this->assertEquals("Test Platform", $platform->name);
        $this->assertEquals($platformBaseContentIri, $platform->platformBaseContent);
        $this->assertSame($platformBaseContentIri, $platform->platformBaseContent);
    }

    public function testPutPlatform(): void
    {
        $client = self::createClient();

        $platformIri = $this->findIriBy(Platform::class, ['name' => 'Test Platform']);

        //assert bad response w/ user
        try {
            $client->request('PUT', $platformIri, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [],
                'auth_bearer' => self::$userToken
            ])->getContent();
        } catch (Exception $e) {
            $this->assertStringContainsString('Réservé aux ADMINs !', $e->getMessage());
            $this->assertEquals(403, $e->getCode());
        }

        $response = $client->request('PUT', $platformIri, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                "name" => "Test Platform Modified",
            ],
            'auth_bearer' => self::$adminToken
        ]);
        $this->assertResponseIsSuccessful();

        $platform = json_decode($response->getContent());
        $this->assertEquals("Test Platform Modified", $platform->name);
    }

    public function testDeletePlatform(): void
    {
        $client = self::createClient();

        $platformIri = $this->findIriBy(Platform::class, ['name' => 'Test Platform Modified']);

        //assert bad response w/ user
        try {
            $client->request('DELETE', $platformIri, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [],
                'auth_bearer' => self::$userToken
            ])->getContent();
        } catch (Exception $e) {
            $this->assertStringContainsString('Réservé aux ADMINs !', $e->getMessage());
            $this->assertEquals(403, $e->getCode());
        }

        $client->request('DELETE', $platformIri, [
            'headers' => ['Content-Type' => 'application/json'],
            'auth_bearer' => self::$adminToken
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertNull($this->findIriBy(Platform::class, ['name' => 'Test Platform Modified']));
    }
}
