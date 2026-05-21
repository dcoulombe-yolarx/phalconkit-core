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

namespace PhalconKit\Tests\Unit\Filter\Sanitize;

use PhalconKit\Filter\Sanitize\Json;
use PhalconKit\Tests\Unit\AbstractUnit;

class JsonTest extends AbstractUnit
{
    public function testInvokeReturnsNullInputAsNull(): void
    {
        $sanitize = new Json();

        $this->assertNull($sanitize());
    }

    public function testInvokeReturnsValidJsonUnchanged(): void
    {
        $sanitize = new Json();

        $this->assertSame('{"name":"Ada"}', $sanitize('{"name":"Ada"}'));
        $this->assertSame('"scalar"', $sanitize('"scalar"'));
        $this->assertSame('[]', $sanitize('[]'));
    }

    public function testInvokeReturnsNullForInvalidJson(): void
    {
        $sanitize = new Json();

        $this->assertNull($sanitize('{"name":'));
        $this->assertNull($sanitize('{name:"Ada"}'));
    }
}
