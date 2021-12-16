<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\PhysicalContainer;
use App\Entity\PhysicalContent;
use App\Entity\PhysicalSupport;
use App\Entity\PlatformBaseContent;
use Exception;

class PlatformBaseContentTest extends ApiTestCase
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
        $client->request('GET', '/api/platform_base_contents');
        $this->assertResponseStatusCodeSame(401);
        $this->assertJsonContains(['message' => 'JWT Token not found']);

        // assert response for USER (and ADMIN) with token
        $client->request('GET', '/api/platform_base_contents', ['auth_bearer' => self::$userToken]);
        $this->assertResponseIsSuccessful();
    }

    public function testGetPlatformBaseContent(): void
    {
        $client = self::createClient();

        // assert response for USER (and ADMIN) with token
        $response = $client->request('GET', 'api/platform_base_contents/1', ['auth_bearer' => self::$userToken]);
        $this->assertResponseIsSuccessful();

        $platformBaseContent = json_decode($response->getContent());
        $this->assertSame('/api/physical_supports/4', $platformBaseContent->physicalSupport);
        $this->assertSame('/api/physical_containers/4', $platformBaseContent->physicalContainer);
        $this->assertSame('/api/physical_contents/2', $platformBaseContent->physicalContent[0]);
    }

    public function testPostPlatformBaseContent(): void
    {
        $client = self::createClient();

        $physicalSupportIri = $this->findIriBy(PhysicalSupport::class, ['name' => 'Cartridge']);
        $physicalContentIri = $this->findIriBy(PhysicalContent::class, ['name' => 'Cover']);
        $physicalContainerIri = $this->findIriBy(PhysicalContainer::class, ['name' => 'Cardboard']);

        // assert can't POST as USER
        try {
            $client->request('POST', 'api/platform_base_contents', [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [],
                'auth_bearer' => self::$userToken
            ])->getContent();
        } catch (Exception $e) {
            $this->assertStringContainsString('Réservé aux ADMINs !', $e->getMessage());
            $this->assertEquals(403, $e->getCode());
        }

        $response = $client->request('POST', 'api/platform_base_contents', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                "physicalSupport" => $physicalSupportIri,
                "physicalContainer" => $physicalContainerIri,
                "physicalContent" => [$physicalContentIri]
            ],
            'auth_bearer' => self::$adminToken
        ]);

        $this->assertResponseIsSuccessful();

        $platformBaseContent = json_decode($response->getContent());
        $this->assertSame($physicalSupportIri, $platformBaseContent->physicalSupport);
        $this->assertSame($physicalContainerIri, $platformBaseContent->physicalContainer);
        $this->assertSame($physicalContentIri, $platformBaseContent->physicalContent[0]);
    }

    public function testPutPlatformBaseContent(): void
    {
        $client = self::createClient();

        $physicalSupportIri = $this->findIriBy(PhysicalSupport::class, ['name' => 'Disk']);
        $physicalContentIri = $this->findIriBy(PhysicalContent::class, ['name' => 'Book']);
        $physicalContainerIri = $this->findIriBy(PhysicalContainer::class, ['name' => 'None']);
        $platformBaseContentIri = $this->findIriBy(PlatformBaseContent::class, ["id" => 2]);

        // assert can't PUT as USER
        try {
            $client->request('PUT', $platformBaseContentIri, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [],
                'auth_bearer' => self::$userToken
            ])->getContent();
        } catch (Exception $e) {
            $this->assertStringContainsString('Réservé aux ADMINs !', $e->getMessage());
            $this->assertEquals(403, $e->getCode());
        }

        $response = $client->request('PUT', $platformBaseContentIri, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                "physicalSupport" => $physicalSupportIri,
                "physicalContainer" => $physicalContainerIri,
                "physicalContent" => [$physicalContentIri]
            ],
            'auth_bearer' => self::$adminToken
        ]);

        $this->assertResponseIsSuccessful();

        $platformBaseContent = json_decode($response->getContent());

        $this->assertSame($physicalSupportIri, $platformBaseContent->physicalSupport);
        $this->assertSame($physicalContainerIri, $platformBaseContent->physicalContainer);
        $this->assertSame($physicalContentIri, $platformBaseContent->physicalContent[0]);
    }

    public function testDeletePlatformBaseContent(): void
    {
        $client = self::createClient();

        $platformBaseContentIri = $this->findIriBy(PlatformBaseContent::class, ["id" => 2]);

        //assert bad response w/ user
        try {
            $client->request('DELETE', $platformBaseContentIri, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [],
                'auth_bearer' => self::$userToken
            ])->getContent();
        } catch (Exception $e) {
            $this->assertStringContainsString('Réservé aux ADMINs !', $e->getMessage());
            $this->assertEquals(403, $e->getCode());
        }

        $client->request('DELETE', $platformBaseContentIri, [
            'headers' => ['Content-Type' => 'application/json'],
            'auth_bearer' => self::$adminToken
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertNull($this->findIriBy(PlatformBaseContent::class, ["id" => 2]));
    }
}
