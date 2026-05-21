<?php

/**
 * This file is part of the Phalcon Kit.
 *
 * (c) Phalcon Kit Team
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PhalconKit\Tests\Unit\Mvc\Router;

use Phalcon\Mvc\Router\RouteInterface;
use PhalconKit\Mvc\Router\ModuleRoute;
use PhalconKit\Tests\Unit\AbstractUnit;

class ModuleRouteTest extends AbstractUnit
{
    public function testBuildsModuleAndLocaleRoutes(): void
    {
        $group = new ModuleRoute([
            'module' => 'api',
            'controller' => 'index',
            'action' => 'index',
        ], ['en', 'fr']);

        $routes = $this->getRoutesByName($group);

        $this->assertSame('/api', $routes['api']->getPattern());
        $this->assertSame('/api/:controller[/]{0,1}', $routes['api-controller']->getPattern());
        $this->assertSame('/api/:controller/:action/:params', $routes['api-controller-action']->getPattern());

        $this->assertSame('/{locale:(en|fr)}/api[/]{0,1}', $routes['locale-api']->getPattern());
        $this->assertSame('/en/api[/]{0,1}', $routes['en-api']->getPattern());
        $this->assertSame('/fr/api/:controller/:action/:params', $routes['fr-api-controller-action']->getPattern());
        $this->assertCount(12, $routes);
    }

    public function testBuildsHostnameRoutesWithoutModulePathPrefix(): void
    {
        $group = new ModuleRoute([
            'module' => 'api',
        ], ['en'], 'api.example.test');

        $routes = $this->getRoutesByName($group);

        $this->assertSame('api.example.test', $group->getHostname());
        $this->assertSame('/', $routes['api-example-test']->getPattern());
        $this->assertSame('/:controller[/]{0,1}', $routes['api-example-test-controller']->getPattern());
        $this->assertSame('/:controller/:action/:params', $routes['api-example-test-controller-action']->getPattern());
        $this->assertSame('/{locale:(en)}[/]{0,1}', $routes['locale-api-example-test']->getPattern());
        $this->assertSame('/en/:controller/:action/:params', $routes['en-api-example-test-controller-action']->getPattern());
        $this->assertCount(9, $routes);
    }

    /**
     * @return array<string, RouteInterface>
     */
    private function getRoutesByName(ModuleRoute $group): array
    {
        $routes = [];
        foreach ($group->getRoutes() as $route) {
            $routes[$route->getName()] = $route;
        }

        return $routes;
    }
}
