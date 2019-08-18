<?php

namespace App\Twig\TextCommands;

use App\Entity\Character;

/**
 * Description of WeaponCommand
 *
 * @author Draeius
 */
class WeaponCommand implements TextCommand {

    public function checkSyntax(string $text): bool {
        $cmdEnd = preg_match('/weapon\(\)/', $text);
        if ($cmdEnd === false) {
            return false;
        }
        return true;
    }

    public function execute(string $text, Character $character): string {
        $armor = $character->getWeapon()->getName();
        return preg_replace('/.*\(\)/', $armor, $text);
    }

}
