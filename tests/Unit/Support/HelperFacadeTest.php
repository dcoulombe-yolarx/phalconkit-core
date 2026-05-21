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

use PhalconKit\Support\Helper;
use PhalconKit\Support\HelperFactory;
use PhalconKit\Tests\Unit\AbstractUnit;

class HelperFacadeTest extends AbstractUnit
{
    private ?HelperFactory $previousHelperFactory = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->previousHelperFactory = Helper::$helperFactory;
        Helper::$helperFactory = null;
    }

    protected function tearDown(): void
    {
        Helper::$helperFactory = $this->previousHelperFactory;

        parent::tearDown();
    }

    public function testGetHelperFactoryReturnsCachedFactory(): void
    {
        $factory = Helper::getHelperFactory();

        $this->assertInstanceOf(HelperFactory::class, $factory);
        $this->assertSame($factory, Helper::getHelperFactory());
    }

    public function testStaticCallsAreDelegatedToHelperFactory(): void
    {
        $this->assertSame('hello-world', Helper::slugify('Hello World'));
        $this->assertSame(
            "first\nsecond",
            Helper::normalizeLineBreaks("first\r\nsecond")
        );
        $this->assertSame(
            'ABC',
            Helper::removeNonPrintable("A\x00B\x1fC")
        );
    }
}
