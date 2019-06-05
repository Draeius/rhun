<?php

namespace App\Entity;

use App\Service\DateTimeService;
use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of ServerTime
 *
 * @author Draeius
 * @Entity
 * @Table(name="server_settings")
 */
class ServerSettings {

    /**
     * @var int 
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * The last time a meteor hit
     * @var DateTime 
     * @Column(type="datetime", nullable=false)
     */
    protected $lastMeteor;

    /**
     * The last time a new day was triggered
     * @var DateTime 
     * @Column(type="datetime", nullable=false)
     */
    protected $lastNewDay;

    /**
     * If the masked Bios should be used
     * @var boolean
     * @Column(type="boolean", nullable=false)
     */
    protected $useMaskedBios = false;

    /**
     *
     * @var DateTime
     * @Column(type="datetime", nullable=true)
     */
    protected $maskedBallStart = NULL;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $priceColoredName = 3000;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $priceColoredTitle = 3000;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $priceCharacterSlot = 1000;

    /**
     * 
     * @var boolean
     * @Column(type="boolean", nullable=false)
     */
    protected $sugarDay = false;

    public function getId() {
        return $this->id;
    }

    public function getLastMeteor() {
        return $this->lastMeteor;
    }

    public function getLastNewDay() {
        return $this->lastNewDay;
    }

    public function getUseMaskedBios() {
        return $this->useMaskedBios;
    }

    public function getMaskedBallStart() {
        return $this->maskedBallStart;
    }

    public function getPriceColoredName() {
        return $this->priceColoredName;
    }

    public function getPriceColoredTitle() {
        return $this->priceColoredTitle;
    }

    public function getSugarDay() {
        return $this->sugarDay;
    }

    public function getPriceCharacterSlot() {
        return $this->priceCharacterSlot;
    }

    public function setPriceCharacterSlot($priceCharacterSlot) {
        $this->priceCharacterSlot = $priceCharacterSlot;
    }

    public function setSugarDay($sugarDay) {
        $this->sugarDay = $sugarDay;
    }

    public function setPriceColoredTitle($priceColoredTitle) {
        $this->priceColoredTitle = $priceColoredTitle;
    }

    public function setPriceColoredName($priceColoredName) {
        $this->priceColoredName = $priceColoredName;
    }

    public function setUseMaskedBios($useMaskedBios) {
        if ($useMaskedBios) {
            $this->maskedBallStart = DateTimeService::getDateTime('NOW');
        } else {
            $this->maskedBallStart = NULL;
        }
        $this->useMaskedBios = $useMaskedBios;
    }

    public function setLastMeteor(DateTime $lastMeteor) {
        $this->lastMeteor = $lastMeteor;
    }

    public function setLastNewDay(DateTime $lastNewDay) {
        $this->lastNewDay = $lastNewDay;
    }

}
