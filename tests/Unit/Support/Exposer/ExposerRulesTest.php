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
use PhalconKit\Support\Exposer\Exposer;
use PhalconKit\Tests\Unit\AbstractUnit;

class ExposerRulesTest extends AbstractUnit
{
    public function testParseColumnsRecursiveNormalizesRootNestedAndEmptyStringRules(): void
    {
        $callback = static fn (): bool => true;

        $this->assertSame([
            '' => false,
            'id' => true,
            'profile.name' => true,
            'profile.email' => false,
            'profile' => false,
            'blank' => true,
            'computed' => $callback,
        ], Exposer::parseColumnsRecursive([
            false,
            'ID',
            'Profile' => [
                'Name',
                'Email' => false,
            ],
            'Blank' => '',
            'Computed' => $callback,
        ]));
    }

    public function testExposeHidesProtectedKeysUnlessEnabled(): void
    {
        $data = [
            'public' => 'visible',
            '_secret' => 'hidden',
        ];

        $this->assertSame([
            'public' => 'visible',
        ], Exposer::expose(Exposer::createBuilder($data)));

        $this->assertSame($data, Exposer::expose(
            Exposer::createBuilder($data, protected: true)
        ));
    }

    public function testCallableRuleCanMutateBuilderValue(): void
    {
        $builder = Exposer::createBuilder([
            'name' => 'Ada',
        ], [
            'name' => static function (Builder $builder): Builder {
                $builder->setValue('Grace');
                return $builder;
            },
        ]);

        $this->assertSame([
            'name' => 'Grace',
        ], Exposer::expose($builder));
    }

    public function testCallableRuleCanIntroduceNestedRules(): void
    {
        $builder = Exposer::createBuilder([
            'profile' => [
                'name' => 'Ada',
                'email' => 'ada@example.test',
            ],
            'settings' => [
                'theme' => 'dark',
            ],
        ], [
            false,
            'profile' => static fn (): array => [
                'name',
            ],
        ]);

        $this->assertSame([
            'profile' => [
                'name' => 'Ada',
            ],
        ], Exposer::expose($builder));
    }
}
