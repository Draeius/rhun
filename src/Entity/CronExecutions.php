<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of CronExecutions
 *
 * @author Matthias
 * @Entity
 * @Table("cron_executions")
 */
class CronExecutions extends RhunEntity {

    /**
     *
     * @var DateTime
     * @Column(type="datetime")
     */
    protected $playerLogout;

    /**
     *
     * @var DateTime
     * @Column(type="datetime")
     */
    protected $newDay;

    public function getByName(string $name): DateTime {
        switch ($name) {
            case 'logout_player':
                return $this->playerLogout;
            case 'new_day':
                return $this->newDay;
        }
    }

    public function setByName(string $name, DateTime $date) {
        switch ($name) {
            case 'logout_player':
                $this->playerLogout = $date;
                break;
            case 'new_day':
                $this->newDay = $date;
                break;
        }
    }

    public function getPlayerLogout(): DateTime {
        return $this->playerLogout;
    }

    public function setPlayerLogout(DateTime $playerLogout) {
        $this->playerLogout = $playerLogout;
    }

    public function getNewDay(): DateTime {
        return $this->newDay;
    }

    public function setNewDay(DateTime $newDay) {
        $this->newDay = $newDay;
    }

}
