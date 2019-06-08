<?php

namespace App\Entity\Location;

use AppBundle\Util\Config\Config;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use FightingBundle\Entity\Enemy;
use FightingBundle\Entity\MonsterOccurance;
use FightingBundle\Entity\SpecialEntity;
use NavigationBundle\Location\FightingLocation;

/**
 * Description of FightingLocationEntity
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_fighting")
 */
class FightingLocationEntity extends PostableLocationEntity {

    /**
     *
     * @var MonsterOccurance
     * @OneToMany(targetEntity="FightingBundle\Entity\MonsterOccurance", mappedBy="location", fetch="EXTRA_LAZY")
     */
    protected $occurances;

    /**
     *
     * @var SpecialEntity
     * @OneToMany(targetEntity="FightingBundle\Entity\SpecialEntity", mappedBy="location", fetch="EXTRA_LAZY")
     */
    protected $specials;

    public function __construct() {
        parent::__construct();
        $this->occurances = new ArrayCollection();
    }

    public function getTemplate() {
        return 'locations/fightingLocation';
    }

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new FightingLocation($manager, $uuid, $this, $config);
    }

    public function getOccurances() {
        return $this->occurances;
    }

    public function getSpecials() {
        return $this->specials;
    }

    public function setOccurances(MonsterOccurance $occurances) {
        $this->occurances = $occurances;
    }

    public function addOccurance(MonsterOccurance $occurance) {
        $this->occurances[] = $occurance;
    }

    public function setSpecials(SpecialEntity $specials) {
        $this->specials = $specials;
    }

    public function addSpecial(SpecialEntity $special) {
        $this->specials[] = $special;
    }

    /**
     * Choose a special from all available specials.
     * @return SpecialEntity
     */
    public function chooseSpecial() {
        $total = 0;
        foreach ($this->specials as $special) {
            $total += $special->getProbability();
        }
        $rand = rand(0, $total);
        foreach ($this->specials as $special) {
            $total -= $special->getProbability();
            if ($rand >= $total) {
                return $special;
            }
        }
        return null;
    }

    /**
     * Chosses an enemy from the available enemies.
     * @return Enemy
     */
    public function chooseMonster() {
        $total = 0;
        foreach ($this->occurances as $occ) {
            $total += $occ->getRate();
        }
        $rand = rand(0, $total);
        foreach ($this->occurances as $occ) {
            $total -= $occ->getRate();
            if ($total < $rand) {
                return $occ->getMonster();
            }
        }
        return null;
    }

}
