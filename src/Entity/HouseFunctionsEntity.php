<?php

namespace App\Entity;

use App\Entity\RhunEntity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of HouseFunctions
 *
 * @author Draeius
 * @Entity
 * @Table(name="house_functions")
 */
class HouseFunctionsEntity extends RhunEntity {

    /**
     * 
     * @var bool
     * @Column(type="boolean")
     */
    protected $savingsActive = false;

    /**
     * 
     * @var int
     * @Column(type="integer")
     */
    protected $savingsPlatin = 0;

    /**
     * 
     * @var int
     * @Column(type="integer")
     */
    protected $savingsGems = 0;

    /**
     * 
     * @var bool
     * @Column(type="boolean")
     */
    protected $titleStorageActive = false;

    public function getSavingsActive() {
        return $this->savingsActive;
    }

    public function getSavingsPlatin() {
        return $this->savingsPlatin;
    }

    public function getSavingsGems() {
        return $this->savingsGems;
    }

    public function getTitleStorageActive() {
        return $this->titleStorageActive;
    }

    public function setTitleStorageActive($titleStorageActive) {
        $this->titleStorageActive = $titleStorageActive;
    }

    public function setSavingsActive($savingsActive) {
        $this->savingsActive = $savingsActive;
    }

    public function setSavingsPlatin($savingsPlatin) {
        $this->savingsPlatin = $savingsPlatin;
    }

    public function setSavingsGems($savingsGems) {
        $this->savingsGems = $savingsGems;
    }

}
