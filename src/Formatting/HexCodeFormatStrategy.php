<?php

namespace App\Formatting;


/**
 * Description of HexCodeFormatStrategy
 *
 * @author Matthias
 */
class HexCodeFormatStrategy implements FormatStrategy {

    public function start(string $string): string {
        if (!empty($string)) {
            if ($string[0] != '`' || $string[1] != '#') {
                return $string;
            }
        }

        $color = mb_substr($string, 1, 7);

        $cleanString = mb_substr($string, 8);

        return '<span style="color: ' . $color . ';">' . $cleanString;
    }

    public function end(string $string): string {
        return '</span>' . $string;
    }

    public function needsClosing(): bool {
        return true;
    }

    public function openAfterStrategy(FormatStrategy $strategy): bool {
        return true;
    }

    public function closeForStrategy(FormatStrategy $nextStrategy): bool {
        if ($nextStrategy instanceof HexCodeFormatStrategy) {
            return true;
        }
        if ($nextStrategy instanceof StandardFormatStrategy) {
            return true;
        }
        return false;
    }

}
