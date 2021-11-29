<?php

namespace App\Tests\Extensions;

use App\DataFixtures\UserFixtures;
use App\Kernel;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use PHPUnit\Runner\AfterLastTestHook;
use PHPUnit\Runner\BeforeFirstTestHook;

class Boot implements BeforeFirstTestHook, AfterLastTestHook
{
    public function executeBeforeFirstTest(): void
    {
        // phpunit --testsuite Unit
        echo sprintf("Setting Up fixtures.... \n\r");

        $kernel = new Kernel('test', false);
        $kernel->boot();
        $container = $kernel->getContainer();

        $databaseTool = $container->get(DatabaseToolCollection::class)->get();

        $databaseTool->loadAllFixtures();

        echo sprintf("Fixtures loaded ! \n\r");        
    }

    public function executeAfterLastTest(): void
    {
        // TODO: Implement executeAfterLastTest() method.
    }

    /**
     * @return string|null
     */
    protected function getPhpUnitParam(string $paramName): ?string
    {
        global $argv;
        $k = array_search("--$paramName", $argv);
        if (!$k) return null;
        return $argv[$k + 1];
    }
}