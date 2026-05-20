#!/bin/bash
#
# This file is part of the Phalcon Kit.
#
# (c) Phalcon Kit Team
#
# For the full copyright and license information, please view the LICENSE.txt
# file that was distributed with this source code.
#
: "${PHPSTAN_MEMORY_LIMIT:=1G}"

phpstan analyse --memory-limit="$PHPSTAN_MEMORY_LIMIT" "$@"
