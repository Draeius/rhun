<?php

namespace App\Formatting;

/**
 * Description of BreakFormatStrategy
 *
 * @author Draeius
 */
class BreakFormatStrategy implements FormatStrategy {

    public function start(String $string): string {
        return '</br>' . mb_substr($string, 2);
    }

    public function end(string $string): string {
        return $string;
    }

    public function needsClosing(): bool {
        return false;
    }

    public function openAfterStrategy(FormatStrategy $strategy): bool {
        return true;
    }

    public function closeForStrategy(FormatStrategy $nextStrategy): bool {
        return false;
    }

}
