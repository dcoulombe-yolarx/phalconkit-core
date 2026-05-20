<?php

declare(strict_types=1);

/**
 * This file is part of the Phalcon Kit.
 *
 * (c) Phalcon Kit Team
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace PhalconKit\Db;

/**
 * {@inheritdoc}
 */
class Profiler extends \Phalcon\Db\Profiler
{
    public function getProfiles(): array
    {
        try {
            return parent::getProfiles();
        } catch (\TypeError) {
            return [];
        }
    }

    public function getTotalElapsedNanoseconds(): float
    {
        try {
            return parent::getTotalElapsedNanoseconds();
        } catch (\TypeError) {
            return 0.0;
        }
    }

    public function getTotalElapsedSeconds(): float
    {
        try {
            return parent::getTotalElapsedSeconds();
        } catch (\TypeError) {
            return 0.0;
        }
    }

    public function toArray(): array
    {
        $profiles = [];
        $profilerProfiles = $this->getProfiles();
        if (!empty($profilerProfiles)) {
            foreach ($profilerProfiles as $profile) {
                $profiles [] = [
                    'sqlStatement' => $profile->getSqlStatement(),
                    'sqlVariables' => $profile->getSqlVariables(),
                    'sqlBindTypes' => $profile->getSqlBindTypes(),
                    'initialTime' => $profile->getInitialTime(),
                    'finalTime' => $profile->getFinalTime(),
                    'elapsedSeconds' => $profile->getTotalElapsedSeconds() / 1000000000,
                ];
            }
        }

        return [
            'profiles' => $profiles,
            'numberTotalStatements' => $this->getNumberTotalStatements(),
            'totalElapsedSeconds' => $this->getTotalElapsedSeconds() / 1000000000,
        ];
    }
}
