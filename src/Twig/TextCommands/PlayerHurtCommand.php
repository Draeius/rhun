<?php

namespace App\Twig\TextCommands;

use App\Entity\Character;

/**
 * Description of PlayerHurtCommand
 *
 * @author Matthias
 */
class PlayerHurtCommand implements TextCommand {

    private static $player_hurt_strings = [
        'Alles ok bei dir? Du siehst nicht gut aus.',
        'Du solltest dich mal untersuchen lassen',
        'Hab ich mich erschreckt, du siehst ganz schön mitgenommen aus.',
        'Gehts dir gut?',
        'Was hast du denn gemacht?',
        'Du siehst leichenblass aus, am besten setzt du dich mal.',
        'Du solltest schnell einen Heiler aufsuchen.',
        'Was haben sie denn mit dir gemacht?'
    ];

    /**
     * 
     * @param string $text
     * @return bool
     */
    public function checkSyntax(string $text): bool {
        $cmdEnd = preg_match('/playerHurt\(("[^"\\\\]*(?:\\.[^"\\\\]*)*[^"\\\\]*(?:\\\\.[^"\\\\]*)*")?\)/', $text);
        if ($cmdEnd === false) {
            return false;
        }
        return true;
    }

    public function execute(string $text, Character $character): string {
        if ($this->isPlayerHurt($character)) {
            return $this->replaceCommand($text, '');
        }

        $replacement = [];
        $replacementDefined = preg_match('/(?<=\(")[^"\\\\]*(?:\\\\.[^"\\\\]*)*(?="\))/', $text, $replacement);

        if ($replacementDefined === 1) {
            return $this->replaceCommand($text, $replacement[0]);
        }

        return $this->replaceCommand($text, $this->getRandomReplacement());
    }

    private function isPlayerHurt(Character $character): bool {
        return $character->getCurrentHP() < 0.1 * $character->getMaxHPWithBuff();
    }

    private function getRandomReplacement(): string {
        $limit = sizeof(self::$player_hurt_strings) * 10;
        $current = 0;
        $pointer = rand(0, $limit);
        foreach (self::$player_hurt_strings as $string) {
            if ($current >= $pointer) {
                return $string;
            }
            $current += 10;
        }
        return self::$player_hurt_strings[0];
    }

    private function replaceCommand(string $text, string $replacement): string {
        return preg_replace('/.*\(("[^"\\\\]*(?:\\.[^"\\\\]*)*[^"\\\\]*(?:\\\\.[^"\\\\]*)*")?\)/', $replacement, $text);
    }

}
