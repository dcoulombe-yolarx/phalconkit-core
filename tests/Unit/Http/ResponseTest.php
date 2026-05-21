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

namespace PhalconKit\Tests\Unit\Http;

use Phalcon\Http\Response as PhalconResponse;
use PhalconKit\Http\Response;
use PhalconKit\Http\ResponseInterface;
use PhalconKit\Tests\Unit\AbstractUnit;

class ResponseTest extends AbstractUnit
{
    public function testResponseExtendsPhalconResponse(): void
    {
        $response = new Response();

        $this->assertInstanceOf(PhalconResponse::class, $response);
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testResponseFromDi(): void
    {
        $response = $this->di->get('response');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testReasonPhraseDefaultsToNullAndTracksStatusCode(): void
    {
        $response = new Response();

        $this->assertNull($response->getReasonPhrase());

        $response->setStatusCode(201, 'Created');

        $this->assertSame(201, $response->getStatusCode());
        $this->assertSame('Created', $response->getReasonPhrase());
    }

    public function testJsonContentUsesInheritedResponseEncoding(): void
    {
        $response = new Response();
        $response->setJsonContent([
            'ok' => true,
            'count' => 2,
        ]);

        $this->assertSame('{"ok":true,"count":2}', $response->getContent());
    }
}
