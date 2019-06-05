<?php

namespace App\Util;

/**
 * Description of Price
 *
 * @author Draeius
 */
class Price {

    private $gold;
    private $platin;
    private $gems;

    public function __construct($gold, $platin, $gems) {
        $this->gold = $gold;
        $this->platin = $platin;
        $this->gems = $gems;
    }

    public function getGold() {
        return $this->gold;
    }

    public function getPlatin() {
        return $this->platin;
    }

    public function getGems() {
        return $this->gems;
    }

    public function setGold($gold) {
        $this->gold = $gold;
    }

    public function setPlatin($platin) {
        $this->platin = $platin;
    }

    public function setGems($gems) {
        $this->gems = $gems;
    }

    public function add(Price $price) {
        $this->gold += $price->getGold();
        $this->gems += $price->getGems();
        $this->platin += $price->getPlatin();
        return $this;
    }

    public function subtract(Price $price) {
        return $this->add($price->multiply(-1));
    }

    public function multiply($factor) {
        $this->gold = round($this->gold * $factor);
        $this->platin = round($this->platin * $factor);
        $this->gems = round($this->gems * $factor);
        return $this;
    }

    public function isZero() {
        return $this->gold == 0 && $this->platin == 0 && $this->gems == 0;
    }

    public function __toString() {
        $result = '';
        if ($this->gold > 0) {
            $result .= $this->gold . ' Gold';
        }
        if ($this->platin > 0) {
            if ($result != '') {
                $result .= ', ';
            }
            $result .= $this->platin . ' Platin';
        }
        if ($this->gems > 0) {
            if ($result != '') {
                $result .= ', ';
            }
            $result .= $this->gems . ' Edelsteine';
        }
        if ($result == '') {
            return 'nichts';
        }
        return $result;
    }

}
