<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity\Traits;

/**
 * Description of EntityLevelTrait
 *
 * @author Draeius
 */
trait EntityLevelTrait {

    /**
     * The characters current exp
     * @var int 
     * @Column(type="integer") 
     */
    protected $exp = 0;

    /**
     * The characters level
     * @var int 
     * @Column(type="integer") 
     */
    protected $level = 1;

    public function addExp(int $amount): void {
        if ($this->level >= 50) {
            return;
        }
        $this->exp += $amount;
        if ($this->exp >= $this->getExpForNextLevel()) {
            $this->exp -= $this->getExpForNextLevel();
            $this->levelUp();
        }
    }

    public function getExpForNextLevel() {
        return round(pow($this->level + 4, 2.7) * 3);
    }

    public function getExp(): int {
        return $this->exp;
    }

    public function getLevel(): int {
        return $this->level;
    }

    abstract function levelUp(): void;
}
