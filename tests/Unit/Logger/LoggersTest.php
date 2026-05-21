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

namespace PhalconKit\Tests\Unit\Logger;

use Phalcon\Logger\Adapter\Noop;
use Phalcon\Logger\Adapter\Stream;
use Phalcon\Logger\Formatter\Line;
use Phalcon\Logger\Logger;
use PhalconKit\Logger\Loggers;
use PhalconKit\Tests\Unit\AbstractUnit;

class LoggersTest extends AbstractUnit
{
    private function createLoggers(array $options = []): Loggers
    {
        return new Loggers(array_replace_recursive([
            'formatters' => [
                'line' => Line::class,
            ],
            'drivers' => [
                'noop' => Noop::class,
                'stream' => Stream::class,
            ],
            'default' => [
                'driver' => 'noop',
                'formatter' => 'line',
            ],
            'loggers' => [],
        ], $options));
    }

    public function testGetFormatterReturnsConfiguredFormatter(): void
    {
        $formatter = $this->createLoggers()->getFormatter('line', [
            'format' => '[%type%] %message%',
        ]);

        $this->assertInstanceOf(Line::class, $formatter);
        $this->assertSame('[%type%] %message%', $formatter->getFormat());
    }

    public function testGetFormatterRejectsUnknownFormatter(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Logger formatter `missing` is not defined.');

        $this->createLoggers()->getFormatter('missing');
    }

    public function testGetAdaptersReturnsConfiguredAdapters(): void
    {
        $adapters = $this->createLoggers()->getAdapters('noop');

        $this->assertArrayHasKey('noop', $adapters);
        $this->assertInstanceOf(Noop::class, $adapters['noop']);
    }

    public function testGetAdaptersAcceptsCommaSeparatedDrivers(): void
    {
        $adapters = $this->createLoggers()->getAdapters('noop,stream', [
            'path' => sys_get_temp_dir() . DIRECTORY_SEPARATOR,
            'filename' => 'phalcon-kit-loggers-test.log',
        ]);

        $this->assertArrayHasKey('noop', $adapters);
        $this->assertArrayHasKey('stream', $adapters);
        $this->assertInstanceOf(Noop::class, $adapters['noop']);
        $this->assertInstanceOf(Stream::class, $adapters['stream']);
        $this->assertCount(2, $adapters);
    }

    public function testGetAdaptersRejectsUnknownDriver(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Logger driver adapter `missing` is not defined.');

        $this->createLoggers()->getAdapters('missing');
    }

    public function testLoadCreatesAndCachesLogger(): void
    {
        $loggers = $this->createLoggers([
            'loggers' => [
                'audit' => [
                    'driver' => 'noop',
                ],
            ],
        ]);

        $logger = $loggers->load('audit');

        $this->assertInstanceOf(Logger::class, $logger);
        $this->assertSame($logger, $loggers->get('audit'));
        $this->assertSame($logger, $loggers->loggers['audit']);
    }

    public function testGetFallsBackToDefaultConfigForUnknownLoggerName(): void
    {
        $loggers = $this->createLoggers();

        $logger = $loggers->get('unknown');

        $this->assertInstanceOf(Logger::class, $logger);
        $this->assertSame($logger, $loggers->get('unknown'));
    }

    public function testSetOverridesCachedLogger(): void
    {
        $loggers = $this->createLoggers();
        $logger = new Logger('manual', [
            'noop' => new Noop(),
        ]);

        $loggers->set('manual', $logger);

        $this->assertSame($logger, $loggers->get('manual'));
    }
}
