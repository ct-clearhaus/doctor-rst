<?php

declare(strict_types=1);

/*
 * This file is part of DOCtor-RST.
 *
 * (c) Oskar Stark <oskarstark@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Rule;

use App\Annotations\Rule\Description;
use App\Annotations\Rule\InvalidExample;
use App\Annotations\Rule\ValidExample;
use App\Value\Lines;

/**
 * @Description("Make sure yarn `--dev` option for `add` command is used at the end.")
 * @ValidExample("yarn add --dev jquery")
 * @InvalidExample("yarn add jquery --dev")
 */
class YarnDevOptionNotAtTheEnd extends AbstractRule implements Rule
{
    public function check(Lines $lines, int $number): ?string
    {
        $lines->seek($number);
        $line = $lines->current();

        if ($line->clean()->match('/yarn add(.*)\-\-dev$/')) {
            return 'Please move "--dev" option before the package';
        }

        return null;
    }
}
