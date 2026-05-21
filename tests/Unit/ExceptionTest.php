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

namespace PhalconKit\Tests\Unit;

use PhalconKit\Exception;
use PhalconKit\Exception\CliException;
use PhalconKit\Exception\HttpException;
use PhalconKit\Exception\WsException;

class ExceptionTest extends AbstractUnit
{
    public function testBaseExceptionPreservesMessageCodeAndPrevious(): void
    {
        $previous = new \RuntimeException('previous');
        $exception = new Exception('message', 123, $previous);

        $this->assertSame('message', $exception->getMessage());
        $this->assertSame(123, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
        $this->assertInstanceOf(\Throwable::class, $exception);
    }

    public function testDomainExceptionsExtendBaseException(): void
    {
        $this->assertInstanceOf(Exception::class, new CliException());
        $this->assertInstanceOf(Exception::class, new HttpException());
        $this->assertInstanceOf(Exception::class, new WsException());
    }
}
