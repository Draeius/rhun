<?php

namespace App\Twig\TextCommands;

use App\Entity\Character;
use App\Service\DateTimeService;

/**
 * Description of DateCommand
 *
 * @author Draeius
 */
class DateCommand implements TextCommand {

    public function checkSyntax(string $text): bool {
        $cmdEnd = preg_match('/date\("[^"\\\\]*(?:\\.[^"\\\\]*)*[^"\\\\]*(?:\\\\.[^"\\\\]*)*"\)/', $text);
        if ($cmdEnd === false) {
            return false;
        }
        return true;
    }

    public function execute(string $text, Character $character): string {
        $dateFormat = [];
        preg_match('/(?<=\(")[^"\\\\]*(?:\\\\.[^"\\\\]*)*(?="\))/', $text, $dateFormat);

        if ($dateFormat[0]) {
            return DateTimeService::getDateTime('now')->format($dateFormat[0]);
        }
        return '';
    }

}
