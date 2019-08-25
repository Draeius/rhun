<?php

namespace App\Entity;

use App\Entity\Traits\EntityColoredNameTrait;
use App\Entity\Traits\PriceTrait;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * Repräsentiert eine Alarmanlage, die Spieler in ihrem Haus installiert haben.
 *
 * @author Draeius
 * @Entity
 * @Table(name="burgler_alarms")
 */
class BurglarAlarm extends RhunEntity {

    use PriceTrait;
    use EntityColoredNameTrait;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $difficulty;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $attribute;

    /**
     *
     * @var string
     * @Column(type="string")
     */
    protected $deathMessage;

    function getDifficulty() {
        return $this->difficulty;
    }

    function getAttribute() {
        return $this->attribute;
    }

    function getDeathMessage() {
        return $this->deathMessage;
    }

    function setDeathMessage($deathMessage) {
        $this->deathMessage = $deathMessage;
    }

    function setDifficulty($difficulty) {
        $this->difficulty = $difficulty;
    }

    function setAttribute($attribute) {
        $this->attribute = $attribute;
    }

}
