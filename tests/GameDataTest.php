<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Game;
use App\Entity\GameData;
use App\Entity\Platform;
use Exception;

class GameDataTest extends ApiTestCase
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
        $client->request('GET', '/api/game_datas');
        $this->assertResponseStatusCodeSame(401);
        $this->assertJsonContains(['message' => 'JWT Token not found']);

        // assert response for USER (and ADMIN) with token
        $client->request('GET', '/api/game_datas', ['auth_bearer' => self::$userToken]);
        $this->assertResponseIsSuccessful();
    }

    public function testGetGame(): void
    {
        $client = self::createClient();

        // assert response for USER (and ADMIN) with token
        $response = $client->request('GET', 'api/games/1', ['auth_bearer' => self::$userToken]);
        $this->assertResponseIsSuccessful();

        $game = json_decode($response->getContent());
        $this->assertSame($game->gameData->title, "Title of Game");
        $this->assertEquals("Platform", $game->platform->name);
        $this->assertEquals("cover_tomb_raider", $game->cover->slug);
    }

    public function testPostGame(): void
    {
        $client = self::createClient();

        $platformIri = $this->findIriBy(Platform::class, ['name' => 'Platform']);
        $gameDataIri = $this->findIriBy(GameData::class, ['title' => 'Title of Game']);
        $date = date(DATE_W3C);

        //assert bad response w/ user
        try {
            $client->request('POST', 'api/games', [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [],
                'auth_bearer' => self::$userToken
            ])->getContent();
        } catch (Exception $e) {
            $this->assertStringContainsString('Réservé aux ADMINs !', $e->getMessage());
            $this->assertEquals(403, $e->getCode());
        }

        //assert good response w/ ADMIN
        $response = $client->request('POST', 'api/games', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                "isbn" => '0000000000',
                "gameData" => $gameDataIri,
                "platform" => $platformIri, // "api/platforms/1"
                "developers" => ["/api/developers/1"],
                "publishers" => ["/api/publishers/1"],
                "releaseDate" => $date,
                "cover" => "/api/cover_objects/1"
            ],
            'auth_bearer' => self::$adminToken
        ]);
        $this->assertResponseIsSuccessful();

        $game = json_decode($response->getContent());

        $this->assertEquals($gameDataIri, $game->gameData);
        $this->assertEquals($platformIri, $game->platform);
        $this->assertEquals($date, $game->releaseDate);
        $this->assertEquals("/api/cover_objects/1", $game->cover);
    }

    public function testPutGame(): void
    {
        $client = self::createClient();

        $gameIri = $this->findIriBy(Game::class, ["isbn" => '0000000000']);
        $date = date(DATE_W3C);

        //assert bad response w/ user
        try {
            $client->request('PUT', $gameIri, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [],
                'auth_bearer' => self::$userToken
            ])->getContent();
        } catch (Exception $e) {
            $this->assertStringContainsString('Réservé aux ADMINs !', $e->getMessage());
            $this->assertEquals(403, $e->getCode());
        }

        $response = $client->request('PUT', $gameIri, [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                "releaseDate" => $date,
            ],
            'auth_bearer' => self::$adminToken
        ]);
        $this->assertResponseIsSuccessful();

        $game = json_decode($response->getContent());
        $this->assertEquals($date, $game->releaseDate);
    }

    public function testDeleteGame(): void
    {
        $client = self::createClient();

        $gameIri = $this->findIriBy(Game::class, ["isbn" => '0000000000']);

        //assert bad response w/ user
        try {
            $client->request('DELETE', $gameIri, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [],
                'auth_bearer' => self::$userToken
            ])->getContent();
        } catch (Exception $e) {
            $this->assertStringContainsString('Réservé aux ADMINs !', $e->getMessage());
            $this->assertEquals(403, $e->getCode());
        }

        $client->request('DELETE', $gameIri, [
            'headers' => ['Content-Type' => 'application/json'],
            'auth_bearer' => self::$adminToken
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertNull($this->findIriBy(Game::class, ["isbn" => '0000000000']));
    }
}
