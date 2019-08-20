<?php

namespace App\Twig;

use App\Entity\Character;
use App\Twig\TextCommands\CommandFactory;
use Symfony\Component\VarDumper\VarDumper;
use Twig_Extension;
use Twig_SimpleFilter;

/**
 * Description of CommandTwigExtension
 *
 * @author Draeius
 */
class CommandTwigExtension extends Twig_Extension {

    public function getFilters() {
        return array(
            new Twig_SimpleFilter('command', array($this, 'parseCommands'))
        );
    }

    public function getName() {
        return 'command_twig_extension';
    }

    public function parseCommands(string $text, Character $character) {
        $array = explode('||', $text);
        if (sizeof($array) === 1) {
            return $text;
        }
        foreach ($array as $key => $part) {
            $end = strpos($part, '(');
            if ($end !== false) {
                $command = substr($part, 0, $end);
                $array[$key] = $this->executeCommand($command, $part, $character);
            }
        }

        return implode('', $array);
    }

    private function executeCommand(string $command, string $part, Character $character): string {
        $cmd = CommandFactory::getCommand($command);
        if ($cmd !== false) {
            if ($cmd->checkSyntax($part)) {
                return preg_replace('/\\\\"/', '"', $cmd->execute($part, $character));
            } else {
                VarDumper::dump('Syntax error in Command ' . $command);
            }
        } else {
            VarDumper::dump('Command ' . $command . ' does not exist.');
        }
        return $part;
    }

}
