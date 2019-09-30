<?php

namespace App\Entity\Option;

use App\Entity\Activity;
use App\Entity\Character;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;
use Symfony\Component\Validator\Tests\Fixtures\Entity;

/**
 * @author Draeius
 * @Entity
 * @Table(name="options")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({
 * "death" = "DeathOption",
 * "rec_buff" = "ReceiveBuffOption",
 * "rem_buff" = "ReoveBuffOption",
 * "health" = "HealthOption",
 * "price" = "PriceOption",
 * "item" = "ItemOption",
 * "port" = "TeleportOption",
 * "text" = "TextOption"
 * })
 */
abstract class Option {

    /**
     * The owner of this biography
     * @var Activity 
     * @ManyToOne(targetEntity="App\Entity\Activity", inversedBy="options")
     * @JoinColumn(name="activity_id", referencedColumnName="id")
     */
    protected $activity;

    /**
     *
     * @var number
     * @Column(type="integer")
     */
    protected $probability = 0;

    /**
     *
     * @var string
     * @Column(type="text")
     */
    protected $text;

    /**
     *
     * @var string
     * @Column(type="text")
     */
    protected $shortNewsText;

    public abstract function execute(EntityManagerInterface $eManager, Character $character);

    function getActivity(): Character {
        return $this->activity;
    }

    function getProbability() {
        return $this->probability;
    }

    function getText() {
        return $this->text;
    }

    function getShortNewsText() {
        return $this->shortNewsText;
    }

    function setActivity(Character $activity) {
        $this->activity = $activity;
    }

    function setProbability($probability) {
        $this->probability = $probability;
    }

    function setText($text) {
        $this->text = $text;
    }

    function setShortNewsText($shortNewsText) {
        $this->shortNewsText = $shortNewsText;
    }

}
