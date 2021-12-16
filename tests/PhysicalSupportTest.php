<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\PhysicalSupport;
use Exception;

class PhysicalSupportTest extends ApiTestCase
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
        $client->request('GET', '/api/physical_supports');
        $this->assertResponseStatusCodeSame(401);
        $this->assertJsonContains(['message' => 'JWT Token not found']);

        // assert response for USER (and ADMIN) with token
        $client->request('GET', '/api/physical_supports', ['auth_bearer' => self::$userToken]);
        $this->assertResponseIsSuccessful();
    }

    public function testGetPhysicalSupport(): void
    {
        $client = self::createClient();

        $physicalSupportIri = $this->findIriBy(PhysicalSupport::class, ["name" => "Cartridge"]);

        // assert response for USER (and ADMIN) with token
        $response = $client->request('GET', $physicalSupportIri, ['auth_bearer' => self::$userToken]);
        $this->assertResponseIsSuccessful();

        $physicalSupport = json_decode($response->getContent());
        $this->assertSame($physicalSupport->name, "Cartridge");
    }

    public function testPostPhysicalSupport(): void
    {
        $client = self::createClient();

        // assert can't POST as USER
        try {
            $client->request('POST', 'api/physical_supports', [
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
            $client->request('POST', 'api/physical_supports', [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    "name" => "Cartridge"
                ],
                'auth_bearer' => self::$adminToken
            ])->getContent();
        } catch (Exception $e) {
            $this->assertResponseStatusCodeSame(500);
            $this->assertStringContainsString("Duplicate entry 'Cartridge'", $e->getMessage());
        }

        $response = $client->request('POST', 'api/physical_supports', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                "name" => "Test"
            ],
            'auth_bearer' => self::$adminToken
        ]);

        $this->assertResponseIsSuccessful();

        $physicalSupport = json_decode($response->getContent());

        $this->assertEquals("Test", $physicalSupport->name);
    }

    public function testPutPhysicalSupport(): void
    {

        $client = self::createClient();

        $physicalSupportIri = $this->findIriBy(PhysicalSupport::class, ["name" => "Test"]);

        // assert can't PUT as USER
        try {
            $client->request('PUT', $physicalSupportIri, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [],
                'auth_bearer' => self::$userToken
            ])->getContent();
        } catch (Exception $e) {
            $this->assertStringContainsString('Réservé aux ADMINs !', $e->getMessage());
            $this->assertEquals(403, $e->getCode());
        }

        $response = $client->request('PUT', $physicalSupportIri, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                "name" => "Modified by Test"
            ],
            'auth_bearer' => self::$adminToken
        ]);

        $this->assertResponseIsSuccessful();

        $physicalSupport = json_decode($response->getContent());

        $this->assertEquals("Modified by Test", $physicalSupport->name);
    }

    public function testDeletePhysicalSupport(): void
    {
        $client = self::createClient();

        $physicalSupportIri = $this->findIriBy(PhysicalSupport::class, ['name' => 'Modified by Test']);

        //assert bad response w/ user
        try {
            $client->request('DELETE', $physicalSupportIri, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [],
                'auth_bearer' => self::$userToken
            ])->getContent();
        } catch (Exception $e) {
            $this->assertStringContainsString('Réservé aux ADMINs !', $e->getMessage());
            $this->assertEquals(403, $e->getCode());
        }

        $client->request('DELETE', $physicalSupportIri, [
            'headers' => ['Content-Type' => 'application/json'],
            'auth_bearer' => self::$adminToken
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertNull($this->findIriBy(PhysicalSupport::class, ['name' => 'Modified by Test']));
    }
}
