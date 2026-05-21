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

use PhalconKit\Filter\Sanitize\Md5;
use PhalconKit\Tests\Unit\AbstractUnit;

class Md5Test extends AbstractUnit
{
    public function testInvokeKeepsLowercaseHexCharactersOnly(): void
    {
        $sanitize = new Md5();

        $this->assertSame(
            'abcdef1234567890',
            $sanitize('abcXYZdef-1234-5678-90')
        );
    }
}
