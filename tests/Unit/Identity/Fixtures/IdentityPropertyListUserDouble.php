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

use PhalconKit\Models\User;

class IdentityPropertyListUserDouble extends User
{
    public mixed $rolelist = 'not-iterable';

    public mixed $typelist = [];

    public mixed $grouplist = null;

    public function hasLoadedRelatedAlias(string $alias): bool
    {
        return false;
    }

    public function hasDirtyRelatedAlias(string $alias): bool
    {
        return false;
    }
}
