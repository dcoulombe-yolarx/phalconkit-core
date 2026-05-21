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

namespace PhalconKit\Tests\Unit\Filter;

use PhalconKit\Filter\Filter;
use PhalconKit\Filter\FilterFactory;
use PhalconKit\Tests\Unit\AbstractUnit;

class FilterFactoryTest extends AbstractUnit
{
    protected function setUp(): void
    {
        /**
         * This setup method is intentionally left empty.
         * This test class does not require any specific initialization or fixtures.
         */
    }
    
    public function testNewInstance(): void
    {
        $filterFactory = new FilterFactory();
        $this->assertInstanceOf(\PhalconKit\Filter\FilterFactory::class, $filterFactory);
        $this->assertInstanceOf(\Phalcon\Filter\FilterFactory::class, $filterFactory);
        
        $filter = $filterFactory->newInstance();
        $this->assertInstanceOf(\PhalconKit\Filter\Filter::class, $filter);
        $this->assertInstanceOf(\Phalcon\Filter\Filter::class, $filter);
    }

    public function testNewInstanceRegistersCustomFilters(): void
    {
        $filter = (new FilterFactory())->newInstance();

        $this->assertSame('abcdef1234567890', $filter->sanitize('abcXYZdef1234567890', [Filter::FILTER_MD5]));
        $this->assertSame('{"ok":true}', $filter->sanitize('{"ok":true}', [Filter::FILTER_JSON]));
        $this->assertNull($filter->sanitize('{"ok":', [Filter::FILTER_JSON]));
        $this->assertSame('127.0.0.1', $filter->sanitize('127.0.0.1', [Filter::FILTER_IPV4]));
        $this->assertSame('', $filter->sanitize('2001:db8::1', [Filter::FILTER_IPV4]));
        $this->assertSame('2001:db8::1', $filter->sanitize('2001:db8::1', [Filter::FILTER_IPV6]));
        $this->assertSame('', $filter->sanitize('127.0.0.1', [Filter::FILTER_IPV6]));
    }
}
