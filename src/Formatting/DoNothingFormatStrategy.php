<?php

namespace App\Formatting;

/**
 * Description of DoNothingFormatStrategy
 *
 * @author Draeius
 */
class DoNothingFormatStrategy implements FormatStrategy {

    public function start(String $string): string {
        return $string;
    }

    public function end(string $string): string {
        return $string;
    }

    public function needsClosing(): bool {
        return false;
    }

    public function openAfterStrategy(FormatStrategy $strategy): bool {
        return false;
    }

    public function closeForStrategy(FormatStrategy $nextStrategy): bool {
        return false;
    }

}
