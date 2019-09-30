<?php

namespace App\Util\Option;

use App\Entity\Option\Option;

/**
 * Beinhaltet Funktionen mit denen man aus mehreren Optionen zufällig eine Auswählen kann.
 *
 * @author Draeius
 */
class OptionPicker {

    /**
     * 
     * @var number 
     */
    private $totalAmount = 0;

    /**
     *
     * @var array 
     */
    private $options = array();

    public function addOption(Option $option): void {
        if ($option == null) {
            return;
        }
        if ($option->getProbability() <= 0) {
            return;
        }
        $this->totalAmount += $option->getProbability();
        array_push($this->options, $option);
    }

    /**
     * 
     * @return Option|null
     */
    public function chooseOption(): ?Option {
        if ($this->totalAmount == 0) {
            return null;
        }
        /* Eins subtrahieren da der Array index bei 0 beginnt und mt_rand das
         * maximum mit einbezieht */
        $rand = mt_rand(0, $this->totalAmount == 0 ? 0 : $this->totalAmount - 1);
        $current = $this->totalAmount;
        foreach ($this->options as $option) {
            $current -= $option->getProbability();
            if ($rand >= $current) {
                return $option;
            }
        }
    }

}
