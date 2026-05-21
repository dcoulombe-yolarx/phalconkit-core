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

namespace PhalconKit\Tests\Unit\Support;

use PhalconKit\Support\Helper\Arr\FlattenKeys;
use PhalconKit\Support\Helper\Arr\RecursiveMap;
use PhalconKit\Support\Helper\Arr\RecursiveStrReplace;
use PhalconKit\Support\Helper\Str\NormalizeLineBreaks;
use PhalconKit\Support\Helper\Str\RemoveNonPrintable;
use PhalconKit\Support\Helper\Str\SanitizeUTF8;
use PhalconKit\Support\Helper\Str\Slugify;
use PhalconKit\Support\HelperFactory;
use PhalconKit\Tests\Unit\AbstractUnit;

class HelperFactoryTest extends AbstractUnit
{
    public function testNewInstanceCanCreatePhalconKitHelpers(): void
    {
        $factory = new HelperFactory();

        $this->assertInstanceOf(RecursiveMap::class, $factory->newInstance('recursiveMap'));
        $this->assertInstanceOf(FlattenKeys::class, $factory->newInstance('flattenKeys'));
        $this->assertInstanceOf(RecursiveStrReplace::class, $factory->newInstance('recursiveStrReplace'));
        $this->assertInstanceOf(Slugify::class, $factory->newInstance('slugify'));
        $this->assertInstanceOf(SanitizeUTF8::class, $factory->newInstance('sanitizeUTF8'));
        $this->assertInstanceOf(RemoveNonPrintable::class, $factory->newInstance('removeNonPrintable'));
        $this->assertInstanceOf(NormalizeLineBreaks::class, $factory->newInstance('normalizeLineBreaks'));
    }

    public function testMagicCallUsesRegisteredPhalconKitHelpers(): void
    {
        $factory = new HelperFactory();

        $this->assertSame('hello-world', $factory->slugify('Hello World'));
        $this->assertSame([
            'root.leaf' => true,
            'root' => false,
        ], $factory->flattenKeys([
            'root' => [
                'leaf',
            ],
        ]));
        $this->assertSame([
            'value' => 'mapped',
        ], $factory->recursiveMap([
            'value' => 'original',
        ], static fn (): string => 'mapped'));
        $this->assertSame([
            'message' => 'Hello Ada',
        ], $factory->recursiveStrReplace([
            'message' => 'Hello :name',
        ], [
            ':name' => 'Ada',
        ]));
    }
}
