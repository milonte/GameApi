<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\PhysicalContainer;
use Exception;

class PhysicalContainerTest extends ApiTestCase
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

    public function getRoute()
    {
        $client = self::createClient();

        // assert response return 401 when no JWT Token provided
        $client->request('GET', '/api/physical_containers');
        $this->assertResponseStatusCodeSame(401);
        $this->assertJsonContains(['message' => 'JWT Token not found']);

        // assert response for USER (and ADMIN) with token
        $client->request('GET', '/api/physical_containers', ['auth_bearer' => self::$userToken]);
        $this->assertResponseIsSuccessful();
    }

    public function testGetPhysicalContainer(): void
    {
        $client = self::createClient();

        $physicalContainerIri = $this->findIriBy(PhysicalContainer::class, ["name" => "CardBoard"]);

        // assert response for USER (and ADMIN) with token
        $response = $client->request('GET', $physicalContainerIri, ['auth_bearer' => self::$userToken]);
        $this->assertResponseIsSuccessful();

        $physicalContainer = json_decode($response->getContent());
        $this->assertSame($physicalContainer->name, "Cardboard");
    }

    public function testPostPhysicalContainer(): void
    {
        $client = self::createClient();

        // assert can't POST as USER
        try {
            $client->request('POST', 'api/physical_containers', [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [],
                'auth_bearer' => self::$userToken
            ])->getContent();
        } catch (Exception $e) {
            $this->assertStringContainsString('Réservé aux ADMINs !', $e->getMessage());
            $this->assertEquals(403, $e->getCode());
        }

        // assert can't POST value already exists
        try {
            $client->request('POST', 'api/physical_containers', [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    "name" => "CardBoard"
                ],
                'auth_bearer' => self::$adminToken
            ])->getContent();
        } catch (Exception $e) {
            $this->assertResponseStatusCodeSame(500);
            $this->assertStringContainsString("Duplicate entry 'CardBoard'", $e->getMessage());
        }

        $response = $client->request('POST', 'api/physical_containers', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                "name" => "Test"
            ],
            'auth_bearer' => self::$adminToken
        ]);

        $this->assertResponseIsSuccessful();

        $physicalContainer = json_decode($response->getContent());

        $this->assertEquals("Test", $physicalContainer->name);
    }

    public function testPutPhysicalContainer(): void
    {

        $client = self::createClient();

        $physicalContainerIri = $this->findIriBy(PhysicalContainer::class, ["name" => "CardBoard"]);

        // assert can't PUT as USER
        try {
            $client->request('PUT', $physicalContainerIri, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [],
                'auth_bearer' => self::$userToken
            ])->getContent();
        } catch (Exception $e) {
            $this->assertStringContainsString('Réservé aux ADMINs !', $e->getMessage());
            $this->assertEquals(403, $e->getCode());
        }

        $response = $client->request('PUT', $physicalContainerIri, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                "name" => "Modified by Test"
            ],
            'auth_bearer' => self::$adminToken
        ]);

        $this->assertResponseIsSuccessful();

        $physicalContainer = json_decode($response->getContent());

        $this->assertEquals("Modified by Test", $physicalContainer->name);
    }

    public function testDeletePhysicalContainer(): void
    {
        $client = self::createClient();

        $physicalContainerIri = $this->findIriBy(PhysicalContainer::class, ['name' => 'Modified by Test']);

        //assert bad response w/ user
        try {
            $client->request('DELETE', $physicalContainerIri, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [],
                'auth_bearer' => self::$userToken
            ])->getContent();
        } catch (Exception $e) {
            $this->assertStringContainsString('Réservé aux ADMINs !', $e->getMessage());
            $this->assertEquals(403, $e->getCode());
        }

        $client->request('DELETE', $physicalContainerIri, [
            'headers' => ['Content-Type' => 'application/json'],
            'auth_bearer' => self::$adminToken
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertNull($this->findIriBy(PhysicalContainer::class, ['name' => 'Modified by Test']));
    }
}
