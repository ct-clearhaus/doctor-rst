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
use App\Handler\RulesHandler;
use App\Helper\PhpHelper;
use App\Helper\TwigHelper;
use App\Helper\XmlHelper;
use App\Helper\YamlHelper;
use App\Rst\RstParser;
use App\Traits\DirectiveTrait;

/**
 * @Description("Make sure that a word is not used twice in a row.")
 * @ValidExample("Please do not use it this way...")
 * @InvalidExample("Please do not not use it this way...")
 */
class AvoidRepetetiveWords extends AbstractRule implements Rule
{
    use DirectiveTrait;

    public static function getGroups(): array
    {
        return [RulesHandler::GROUP_SONATA, RulesHandler::GROUP_SYMFONY];
    }

    public function check(\ArrayIterator $lines, int $number)
    {
        $lines->seek($number);
        $line = $lines->current();

        if (RstParser::isDirective($line)
            || RstParser::isLink($line)
            || RstParser::isBlankLine($line)
            || RstParser::isTable($line)
            || ($this->in(RstParser::DIRECTIVE_CODE_BLOCK, $lines, $number) && (
                !PhpHelper::isComment($line)
                && !XmlHelper::isComment($line)
                && !TwigHelper::isComment($line)
                && !YamlHelper::isComment($line)))
        ) {
            return;
        }

        $line = RstParser::clean($line);

        $words = explode(' ', $line);

        foreach ($words as $key => $word) {
            if (0 === $key) {
                continue;
            }

            if (isset($words[$key + 1]) && !empty($words[$key + 1]) && 1 < \strlen($word) && $words[$key + 1] == $word) {
                return sprintf('The word "%s" is used more times in a row.', $word);
            }
        }
    }
}