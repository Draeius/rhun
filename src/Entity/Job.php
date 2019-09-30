<?php

namespace App\Entity;

use App\Entity\Traits\EntityAttributesTrait;
use App\Entity\Traits\EntityColoredNameTrait;
use App\Util\Price;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Repository\RepositoryFactory;

/**
 * Description of Job
 *
 * @author Draeius
 * @Entity
 * @Table(name="jobs")
 */
class Job extends RhunEntity {

    use EntityColoredNameTrait;
    use EntityAttributesTrait;

    /**
     * @ManyToMany(targetEntity="Location", fetch="EXTRA_LAZY")
     * @JoinTable(
     *  name="job_location",
     *  joinColumns={
     *      @JoinColumn(name="job_id", referencedColumnName="id")
     *  },
     *  inverseJoinColumns={
     *      @JoinColumn(name="location_id", referencedColumnName="id")
     *  }
     * )
     */
    protected $locations;

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

    public function isSuitable(Character $char) {
        foreach (Attribute::toArray() as $attribute) {
            if ($char->getAttribute($attribute) < $this->getAttribute($attribute)) {
                return 'Du hast zu wenig ' . Attribute::getName($attribute);
            }
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

    public function getSalary(): Price {
        return new Price($this->goldSalary, $this->platinSalary, 0);
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

    function getLocations() {
        return $this->locations;
    }

    function setLocations($locations) {
        $this->locations = $locations;
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
