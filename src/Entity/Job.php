<?php

namespace App\Entity;

use app\model\repositories\RepositoryFactory;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;

/**
 * Description of Job
 *
 * @author Draeius
 * @Entity
 * @Table(name="jobs")
 */
class Job {

    /**
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     *
     * @var string
     * @Column(type="string", length=64)
     */
    protected $name;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $intRequirement;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $strRequirement;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $agiRequirement;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $chaRequirement;

    /**
     *
     * @var Job
     * @OneToOne(targetEntity="Job")
     * @JoinColumn(name="promote_id", referencedColumnName="id", nullable=true)
     */
    protected $promoteTo;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $goldSalary;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $platinSalary;

    /**
     * @var int
     * @Column(type="integer")
     */
    protected $staminaDrain;

    public function getId() {
        return $this->id;
    }

    public function isSuitable(Character $char) {
        if ($char->getAgility() < $this->agiRequirement) {
            return "Du hast nicht genug Agilität.";
        }
        if ($char->getCharme() < $this->chaRequirement) {
            return "Du bist nicht charmant genug.";
        }
        if ($char->getStrength() < $this->strRequirement) {
            return "Du bist nicht stark genug.";
        }
        if ($char->getIntelligence() < $this->intRequirement) {
            return "Du bist nicht intelligent genug";
        }
        $rep = RepositoryFactory::getRepositoryByClass('Job');
        $job = $rep->findByPromoteTo($this);
        //gibt es einen Beruf der zu diesem hier befördert wird?
        if (!$job) {
            return true;
        }
        //hat der charakter überhaupt einen Beruf?
        if ($char->getJob() == null) {
            return "Du hast nicht genug Erfahrung mit dieser Art von Tätigkeit um diesen Beruf ausüben zu können.";
        }
        //ist dieser Beruf der, der zu diesem hier befördert wird?
        if ($char->getJob()->getPromoteTo()->getId() == $this->getId()) {
            return true;
        }
        return "Du hast nicht genug Erfahrung mit dieser Art von Tätigkeit um diesen Beruf ausüben zu können.";
    }

    public function getIntRequirement() {
        return $this->intRequirement;
    }

    public function getStrRequirement() {
        return $this->strRequirement;
    }

    public function getAgiRequirement() {
        return $this->agiRequirement;
    }

    public function getChaRequirement() {
        return $this->chaRequirement;
    }

    public function getPromoteTo() {
        return $this->promoteTo;
    }

    public function getGoldSalary() {
        return $this->goldSalary;
    }

    public function getPlatinSalary() {
        return $this->platinSalary;
    }

    public function getStaminaDrain() {
        return $this->staminaDrain;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setStaminaDrain($staminaDrain) {
        $this->staminaDrain = $staminaDrain;
    }

    public function setIntRequirement($intRequirement) {
        $this->intRequirement = $intRequirement;
    }

    public function setStrRequirement($strRequirement) {
        $this->strRequirement = $strRequirement;
    }

    public function setAgiRequirement($agiRequirement) {
        $this->agiRequirement = $agiRequirement;
    }

    public function setChaRequirement($chaRequirement) {
        $this->chaRequirement = $chaRequirement;
    }

    public function setPromoteTo(Job $promoteTo) {
        $this->promoteTo = $promoteTo;
    }

    public function setGoldSalary($goldSalary) {
        $this->goldSalary = $goldSalary;
    }

    public function setPlatinSalary($platinSalary) {
        $this->platinSalary = $platinSalary;
    }

}
