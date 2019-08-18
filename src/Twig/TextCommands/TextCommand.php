<?php

namespace App\Twig\TextCommands;

use App\Entity\Character;

/**
 * Description of TextCommand
 *
 * @author Draeius
 */
interface TextCommand {

    /**
     * 
     * @param string $text
     * @param Character $character
     */
    public function execute(string $text, Character $character): string;

    /**
     * 
     * @param string $text
     */
    public function checkSyntax(string $text): bool;
}
