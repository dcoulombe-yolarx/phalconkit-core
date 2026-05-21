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

namespace PhalconKit\Tests\Unit\Identity\Fixtures;

use PhalconKit\Models\Interfaces\UserInterface;

class IdentityUserModelDouble
{
    /**
     * @var array<int, UserInterface>
     */
    public static array $findFirstWithById = [];

    public static ?UserInterface $findFirstResult = null;

    public static array $findFirstWithCalls = [];

    public static array $findFirstCalls = [];

    public static function reset(): void
    {
        self::$findFirstWithById = [];
        self::$findFirstResult = null;
        self::$findFirstWithCalls = [];
        self::$findFirstCalls = [];
    }

    public static function findFirstWith(array $with, array $params): ?UserInterface
    {
        self::$findFirstWithCalls[] = [$with, $params];
        $id = $params['bind']['id'] ?? null;

        return self::$findFirstWithById[$id] ?? null;
    }

    public static function findFirst(array $params): ?UserInterface
    {
        self::$findFirstCalls[] = $params;

        return self::$findFirstResult;
    }
}
