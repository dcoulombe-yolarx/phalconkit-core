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

namespace PhalconKit\Tests\Unit\Support\Exposer;

use PhalconKit\Support\Exposer\Builder;
use PhalconKit\Tests\Unit\AbstractUnit;

class BuilderTest extends AbstractUnit
{
    public function testProcessKeyNormalizesRootAndDotPathSegments(): void
    {
        $this->assertSame('', Builder::processKey());
        $this->assertSame('', Builder::processKey(''));
        $this->assertSame('', Builder::processKey('123'));
        $this->assertSame('profile.name', Builder::processKey(' Profile   Name '));
        $this->assertSame('profile.name', Builder::processKey('..Profile...Name..'));
        $this->assertSame('profile.name', Builder::processKey('Profile. . .Name'));
    }

    public function testGetFullKeyHandlesRootAndNestedContext(): void
    {
        $builder = new Builder();
        $this->assertSame('', $builder->getFullKey());

        $builder->setKey('Name');
        $this->assertSame('name', $builder->getFullKey());

        $builder->setContextKey('Profile');
        $this->assertSame('profile.name', $builder->getFullKey());

        $builder->setKey(null);
        $this->assertSame('profile', $builder->getFullKey());

        $builder->setContextKey(null);
        $this->assertSame('', $builder->getFullKey());
    }

    public function testAccessorsPreserveBuilderState(): void
    {
        $builder = new Builder();
        $value = ['id' => 1];
        $parent = ['items' => [$value]];
        $columns = ['id' => true];

        $builder->setValue($value);
        $builder->setParent($parent);
        $builder->setColumns($columns);
        $builder->setField('id');
        $builder->setExpose(false);
        $builder->setProtected(true);

        $this->assertSame($value, $builder->getValue());
        $this->assertSame($parent, $builder->getParent());
        $this->assertSame($columns, $builder->getColumns());
        $this->assertSame('id', $builder->getField());
        $this->assertFalse($builder->getExpose());
        $this->assertTrue($builder->getProtected());
    }
}
