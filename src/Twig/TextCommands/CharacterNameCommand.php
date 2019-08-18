<?php

namespace App\Twig\TextCommands;

use App\Entity\Character;
use App\Service\CharacterService;

/**
 * Description of CharacterNameCommand
 *
 * @author Matthias
 */
class CharacterNameCommand implements TextCommand {

    public function checkSyntax(string $text): bool {
        $cmdEnd = preg_match('/charName\(((true|false),(true|false))?\)/', $text);
        if ($cmdEnd === false) {
            return false;
        }
        return true;
    }

    public function execute(string $text, Character $character): string {
        $masked = [];
        preg_match('/(?<=charName\()((true|false),(true|false))?(?=\))/', $text, $masked);
        if (!$masked[0]) {
            $useMasked = true;
            $showTitle = true;
        } else {
            $useMasked = filter_var($masked[2], FILTER_VALIDATE_BOOLEAN);
            $showTitle = filter_var($masked[3], FILTER_VALIDATE_BOOLEAN);
        }

        $name = $this->getName($character, $useMasked, $showTitle);

        return preg_replace('/charName\((true|false)?\)/', $name, $text);
    }

    public function getName(Character $character, bool $useMasked, bool $showTitle): string {
        if ($showTitle || $useMasked) {
            $name = CharacterService::getNameString($character, $useMasked);
        }
        if (!$showTitle && $useMasked) {
            $coloredName = $character->getColoredName();
            if (strpos($name, $coloredName) !== false) {
                return $coloredName;
            }
        } else if (!$showTitle && !$useMasked) {
            return $character->getColoredName();
        }
        return $name;
    }

}
