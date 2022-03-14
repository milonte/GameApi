<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\PhysicalContent;
use Exception;

class PhysicalContentTest extends ApiTestCase
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
        $client->request('GET', '/api/physical_contents');
        $this->assertResponseStatusCodeSame(401);
        $this->assertJsonContains(['message' => 'JWT Token not found']);

        // assert response for USER (and ADMIN) with token
        $client->request('GET', '/api/physical_contents', ['auth_bearer' => self::$userToken]);
        $this->assertResponseIsSuccessful();
    }

    public function testGetPhysicalContent(): void
    {
        $client = self::createClient();

        $physicalContentIri = $this->findIriBy(PhysicalContent::class, ["name" => "Book"]);

        // assert response for USER (and ADMIN) with token
        $response = $client->request('GET', $physicalContentIri, ['auth_bearer' => self::$userToken]);
        $this->assertResponseIsSuccessful();

        $physicalContent = json_decode($response->getContent());
        $this->assertSame($physicalContent->name, "Book");
    }

    public function testPostPhysicalContent(): void
    {
        $client = self::createClient();

        // assert can't POST as USER
        try {
            $client->request('POST', 'api/physical_contents', [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [],
                'auth_bearer' => self::$userToken
            ])->getContent();
        } catch (Exception $e) {
            $this->assertStringContainsString('Réservé aux ADMINs !', $e->getMessage());
            $this->assertEquals(403, $e->getCode());
        }

        $response = $client->request('POST', 'api/physical_contents', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                "name" => "Test"
            ],
            'auth_bearer' => self::$adminToken
        ]);

        $this->assertResponseIsSuccessful();

        $physicalContent = json_decode($response->getContent());

        $this->assertEquals("Test", $physicalContent->name);
    }

    public function testPutPhysicalContent(): void
    {

        $client = self::createClient();

        $physicalContentIri = $this->findIriBy(PhysicalContent::class, ["name" => "Test"]);

        // assert can't PUT as USER
        try {
            $client->request('PUT', $physicalContentIri, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [],
                'auth_bearer' => self::$userToken
            ])->getContent();
        } catch (Exception $e) {
            $this->assertStringContainsString('Réservé aux ADMINs !', $e->getMessage());
            $this->assertEquals(403, $e->getCode());
        }

        $response = $client->request('PUT', $physicalContentIri, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                "name" => "Modified by Test"
            ],
            'auth_bearer' => self::$adminToken
        ]);

        $this->assertResponseIsSuccessful();

        $physicalContent = json_decode($response->getContent());

        $this->assertEquals("Modified by Test", $physicalContent->name);
    }

    public function testDeletePhysicalContent(): void
    {
        $client = self::createClient();

        $physicalContentIri = $this->findIriBy(PhysicalContent::class, ['name' => 'Modified by Test']);

        //assert bad response w/ user
        try {
            $client->request('DELETE', $physicalContentIri, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [],
                'auth_bearer' => self::$userToken
            ])->getContent();
        } catch (Exception $e) {
            $this->assertStringContainsString('Réservé aux ADMINs !', $e->getMessage());
            $this->assertEquals(403, $e->getCode());
        }

        $client->request('DELETE', $physicalContentIri, [
            'headers' => ['Content-Type' => 'application/json'],
            'auth_bearer' => self::$adminToken
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertNull($this->findIriBy(PhysicalContent::class, ['name' => 'Modified by Test']));
    }
}
