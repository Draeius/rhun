<?php

namespace App\Twig\TextCommands;

use App\Entity\Character;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Description of GenderiseCommand
 *
 * @author Matthias
 */
class GenderizeCommand implements TextCommand {

    /**
     * 
     * @param string $text
     * @return bool
     */
    public function checkSyntax(string $text): bool {
        $cmdEnd = preg_match('/genderize\("[^"\\\\]*(?:\\\\.[^"\\\\]*)*","[^"\\\\]*(?:\\\\.[^"\\\\]*)*"\)/', $text);
        if ($cmdEnd === false) {
            VarDumper::dump('Syntax Error in Command genderise.');
            return false;
        }
        return true;
    }

    /**
     * 
     * @param string $text
     * @param Character $character
     * @return string
     */
    public function execute(string $text, Character $character): string {
        $replacement = [];
        if ($character->getGender()) {
            preg_match('/(?<=\(")[^"\\\\]*(?:\\.[^"\\\\]*)*(?=",)/', $text, $replacement);
        } else {
            preg_match('/(?<=,")[^"\\\\]*(?:\\.[^"\\\\]*)*(?="\))/', $text, $replacement);
        }
        if (empty($replacement)) {
            VarDumper::dump('Syntax Error in Command genderise.');
        }

        return preg_replace('/.*\("[^"\\\\]*(?:\\\\.[^"\\\\]*)*","[^"\\\\]*(?:\\\\.[^"\\\\]*)*"\)/', $replacement[0], $text);
    }

}
