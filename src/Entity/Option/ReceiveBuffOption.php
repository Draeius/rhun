<?php

namespace App\Entity\Option;

use App\Entity\Buff;
use App\Entity\BuffTemplate;
use App\Entity\Character;
use App\Service\DateTimeService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * 
 * @Entity
 * @Table(name="options_rec_buff")
 */
class ReceiveBuffOption extends Option {

    /**
     *
     * @var BuffTemplate
     * @ManyToOne(targetEntity="App\Entity\BuffTemplate")
     * @JoinColumn(name="buff_templ_id", referencedColumnName="id")
     */
    protected $buffTemplate;

    /**
     *
     * @var number
     * @Column(type="integer")
     */
    protected $modifier;

    /**
     *
     * @var number
     * @Column(type="integer")
     */
    protected $maxRounds;

    /**
     *
     * @var number
     * @Column(type="integer")
     */
    protected $neededArmorType;

    /**
     *
     * @var number
     * @Column(type="integer")
     */
    protected $neededWeaponType;

    public function execute(EntityManagerInterface $eManager, Character $character) {
        if ($this->neededArmorType !== NULL && $this->neededArmorType != $character->getArmor()->getArmorTemplate()->getArmorType()) {
            return;
        }
        if ($this->neededWeaponType !== NULL && $this->neededWeaponType != $character->getArmor()->getWeaponTemplate()->getWeaponType()) {
            return;
        }

        $buff = new Buff();
        $buff->setTemplate($this->buffTemplate);
        if ($this->modifier && $this->modifier != '') {
            $date = DateTimeService::getDateTime('NOW');
            $date->modify($this->modifier);
            $buff->setEndDate($date);
        }
        if ($this->maxRounds > 0) {
            $buff->setMaxRounds($this->maxRounds);
        }

        $previous = $character->addBuff($buff);
        if ($previous) {
            $eManager->remove($previous);
        }
        if ($buff->getOwner()) {
            $eManager->persist($buff);
        }
        $eManager->flush();
    }

}
