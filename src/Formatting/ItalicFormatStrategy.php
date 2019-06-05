<?php

namespace App\Formatting;

/**
 * Description of ItalicFormatStrategy
 *
 * @author Matthias
 */
class ItalicFormatStrategy implements FormatStrategy {

    public function start(String $string): string {
        return '<span style="font-style: italic;">' . mb_substr($string, 2);
    }

    public function end(string $string): string {
        if (!empty($string)) {
            if ($string[0] == '`' && $string[1] == 'i') {
                return '</span>' . mb_substr($string, 2);
            }
        }
        return '</span>' . $string;
    }

    public function needsClosing(): bool {
        return true;
    }

    public function openAfterStrategy(FormatStrategy $strategy): bool {
        if ($strategy instanceof ItalicFormatStrategy) {
            return false;
        }
        return true;
    }

    public function closeForStrategy(FormatStrategy $nextStrategy): bool {
        if ($nextStrategy instanceof HexCodeFormatStrategy) {
            return true;
        }
        if ($nextStrategy instanceof StandardFormatStrategy) {
            return true;
        }
        if ($nextStrategy instanceof BoldFormatStrategy) {
            return true;
        }
        if ($nextStrategy instanceof ItalicFormatStrategy) {
            return true;
        }
        return false;
    }

}
