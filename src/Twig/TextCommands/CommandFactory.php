<?php

namespace App\Twig\TextCommands;

/**
 * Description of CommandFactory
 *
 * @author Matthias
 */
class CommandFactory {

    /**
     * 
     * @param string $commandName
     * @return TextCommand|false
     */
    public static function getCommand(string $commandName) {
        switch ($commandName) {
            case 'genderize':
                return new GenderizeCommand();
            case 'playerHurt':
                return new PlayerHurtCommand();
            case 'bank':
                return new BankCommand();
            case 'charName':
                return new CharacterNameCommand();
            case 'date':
                return new DateCommand();
            case 'weapon':
                return new WeaponCommand();
            case 'armor':
                return new ArmorCommand();
        }
        return false;
    }

}
