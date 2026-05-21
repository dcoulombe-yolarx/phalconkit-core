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

namespace PhalconKit\Tests\Unit\Config;

use PhalconKit\Tests\Unit\AbstractUnit;
use PhalconKit\Config\Config;

class ConfigTest extends AbstractUnit
{
    public function testPathToArray(): void
    {
        $config = new Config();
        
        $paths = [
            'test',
            'test1.test2',
            'test2.test3.test4.',
            '0',
            '1.2',
            '2.3.4.',
            '!@#$%^&*()',
        ];
        
        $tests = [
            ['value' => '', 'expected' => ['']],
            ['value' => null, 'expected' => null],
            ['value' => ['test'], 'expected' => ['test']],
            ['value' => ['test' => 'test2'], 'expected' => ['test' => 'test2']],
            ['value' => ['test', 'test'], 'expected' => ['test', 'test']],
            ['value' => 'test', 'expected' => ['test']],
            ['value' => '!@#$%^&*()', 'expected' => ['!@#$%^&*()']],
            ['value' => 1, 'expected' => [1]],
            ['value' => 1.1, 'expected' => [1.1]],
            ['value' => true, 'expected' => [true]],
            ['value' => false, 'expected' => [false]],
            ['value' => Config::class, 'expected' => [Config::class]],
        ];
        
        foreach ($paths as $path) {
            foreach ($tests as $test) {
                $config->remove($path);
                
                $nullOrArray = isset($test['value']) ? (array)$test['value'] : $test['value'];
                $actual = $config->pathToArray($path, $nullOrArray);
                $this->assertEquals($test['expected'], $actual);
                $this->assertNull($config->pathToArray($path));
                if (!is_null($actual)) {
                    $this->assertIsArray($actual);
                }
                
                $config->set($path, $test['value']);
                $actual = $config->pathToArray($path);
                $this->assertEquals($test['expected'], $actual);
                
                $config->set($path, (object)$test['value']);
                $actual = $config->pathToArray($path);
                $this->assertIsArray($actual);
                $this->assertEquals((array)(object)$test['value'], $actual, $path . ' : ' . json_encode($test));
            }
        }
    }

    public function testPathToArrayConvertsNestedConfigToArray(): void
    {
        $config = new Config([
            'database' => new Config([
                'host' => 'localhost',
                'port' => 3306,
            ]),
        ]);

        $this->assertSame([
            'host' => 'localhost',
            'port' => 3306,
        ], $config->pathToArray('database'));
    }

    public function testMergeAppendRecursivelyAppendsNumericValuesAndReplacesAssociativeValues(): void
    {
        $config = new Config([
            'providers' => [
                'first',
            ],
            'database' => [
                'host' => 'db',
                'options' => [
                    'timeout' => 10,
                ],
            ],
        ]);

        $result = $config->merge([
            'providers' => [
                'second',
            ],
            'database' => [
                'host' => 'db2',
                'options' => [
                    'charset' => 'utf8mb4',
                ],
            ],
        ], true);

        $this->assertSame($config, $result);
        $this->assertSame([
            'first',
            'second',
        ], $config->pathToArray('providers'));
        $this->assertSame([
            'host' => 'db2',
            'options' => [
                'timeout' => 10,
                'charset' => 'utf8mb4',
            ],
        ], $config->pathToArray('database'));
    }

    public function testMergeAppendRejectsInvalidDataType(): void
    {
        $config = new Config();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid data type for merge.');

        $config->merge('invalid', true);
    }

    public function testGetDateTimeUsesProvidedBaseDate(): void
    {
        $config = new Config();
        $baseDate = new \DateTimeImmutable('2026-01-01 00:00:00');

        $this->assertSame(
            '2026-01-08 00:00:00',
            $config->getDateTime('+7 days', $baseDate)->format('Y-m-d H:i:s')
        );
    }
}
