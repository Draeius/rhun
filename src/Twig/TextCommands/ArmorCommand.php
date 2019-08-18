<?php

namespace App\Twig\TextCommands;

use App\Entity\Character;

/**
 * Description of ArmorCommand
 *
 * @author Draeius
 */
class ArmorCommand implements TextCommand {

    public function checkSyntax(string $text): bool {
        $cmdEnd = preg_match('/armor\(\)/', $text);
        if ($cmdEnd === false) {
            return false;
        }
        return true;
    }

    public function execute(string $text, Character $character): string {
        $armor = $character->getArmor()->getName();
        return preg_replace('/.*\(\)/', $armor, $text);
    }

}
