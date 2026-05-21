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

use PhalconKit\Filter\Sanitize\IPv6;
use PhalconKit\Tests\Unit\AbstractUnit;

class IPv6Test extends AbstractUnit
{
    public function testInvokeReturnsValidIpv6Address(): void
    {
        $sanitize = new IPv6();

        $this->assertSame('2001:db8::1', $sanitize('2001:db8::1'));
    }

    public function testInvokeRejectsInvalidOrWrongFamilyAddress(): void
    {
        $sanitize = new IPv6();

        $this->assertSame('', $sanitize());
        $this->assertSame('', $sanitize('2001:db8:::1'));
        $this->assertSame('', $sanitize('192.168.0.1'));
    }
}
