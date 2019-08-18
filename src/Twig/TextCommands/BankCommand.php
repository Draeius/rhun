<?php

namespace App\Twig\TextCommands;

use App\Entity\Character;
use App\Util\Price;

/**
 * Description of BankCommand
 *
 * @author Matthias
 */
class BankCommand implements TextCommand {

    public function checkSyntax(string $text): bool {
        $cmdEnd = preg_match('/bank\(\)/', $text);
        if ($cmdEnd === false) {
            return false;
        }
        return true;
    }

    public function execute(string $text, Character $character): string {$replacement = [];
        $price = new Price($character->getWallet()->getBankGold(), $character->getWallet()->getBankPlatin(), 0);

        return preg_replace('/.*\(\)/', $price, $text);
    }

}
