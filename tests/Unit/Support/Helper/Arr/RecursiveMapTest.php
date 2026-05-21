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

namespace PhalconKit\Tests\Unit\Support\Helper\Arr;

use PhalconKit\Support\Helper\Arr\RecursiveMap;
use PhalconKit\Tests\Unit\AbstractUnit;

class RecursiveMapTest extends AbstractUnit
{
    public function testInvokeMapsLeafValuesAndPreservesNestedShape(): void
    {
        $recursiveMap = new RecursiveMap();

        $result = $recursiveMap([
            'name' => 'Ada',
            'flags' => [
                'enabled' => true,
                'count' => 3,
            ],
            'tags' => ['alpha', 'beta'],
        ], static fn (mixed $value): string => get_debug_type($value));

        $this->assertSame([
            'name' => 'string',
            'flags' => [
                'enabled' => 'bool',
                'count' => 'int',
            ],
            'tags' => ['string', 'string'],
        ], $result);
    }

    public function testProcessLeavesEmptyArraysUntouched(): void
    {
        $this->assertSame([
            'empty' => [],
            'nested' => [
                'empty' => [],
            ],
        ], RecursiveMap::process([
            'empty' => [],
            'nested' => [
                'empty' => [],
            ],
        ], static fn (mixed $value): string => 'mapped'));
    }
}
