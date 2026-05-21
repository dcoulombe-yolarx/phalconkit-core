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

namespace PhalconKit\Tests\Unit\Fractal\Serializer;

use PhalconKit\Fractal\Serializer\RawArraySerializer;
use PhalconKit\Tests\Unit\AbstractUnit;

class RawArraySerializerTest extends AbstractUnit
{
    public RawArraySerializer $rawArraySerializer;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->rawArraySerializer = new RawArraySerializer();
    }
    
    public function testCollection(): void
    {
        $this->assertEquals([], $this->rawArraySerializer->collection('key', []));
    }

    public function testCollectionReturnsDataWithoutResourceKeyWrapper(): void
    {
        $data = [
            ['id' => 1],
            ['id' => 2],
        ];

        $this->assertSame($data, $this->rawArraySerializer->collection('users', $data));
        $this->assertArrayNotHasKey('users', $this->rawArraySerializer->collection('users', $data));
    }
    
    public function testItem(): void
    {
        $this->assertEquals([], $this->rawArraySerializer->item('key', []));
    }

    public function testItemReturnsDataWithoutResourceKeyWrapper(): void
    {
        $data = ['id' => 1, 'name' => 'Ada'];

        $this->assertSame($data, $this->rawArraySerializer->item('user', $data));
        $this->assertArrayNotHasKey('user', $this->rawArraySerializer->item('user', $data));
    }
    
    public function testNull(): void
    {
        $this->assertEquals([], $this->rawArraySerializer->null());
    }
}
