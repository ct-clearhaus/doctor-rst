<?php

declare(strict_types=1);

/*
 * This file is part of the rst-checker.
 *
 * (c) Oskar Stark <oskarstark@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Rule\Sonata;

use App\Rule\Rule;

class NoAdminYaml implements Rule
{
    public function check(\ArrayIterator $lines, int $number)
    {
        $lines->seek($number);
        $line = $lines->current();

        if (preg_match('/admin\.yml/', $line)) {
            return 'Please use "services.yaml" instead of "admin.yml"';
        }

        if (preg_match('/admin\.yaml/', $line)) {
            return 'Please use "services.yaml" instead of "admin.yaml"';
        }
    }
}