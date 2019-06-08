<?php

namespace App\Entity\Location;

use AppBundle\Entity\Job;
use AppBundle\Util\Config\Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;
use App\Entity\Location\PostableLocationEntity;
use NavigationBundle\Location\JobLocation;

/**
 * Description of JobLocationEntity
 *
 * @author Draeius
 * @Entity
 * @Table(name="location_workplace")
 */
class JobLocationEntity extends PostableLocationEntity {

    /**
     *
     * @var Job[]
     * @ManyToMany(targetEntity="AppBundle\Entity\Job", fetch="EXTRA_LAZY")
     * @JoinTable(name="job_workplaces",
     *      joinColumns={@JoinColumn(name="location_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="job_id", referencedColumnName="id")}
     *      )
     */
    protected $jobs;

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new JobLocation($manager, $uuid, $this, $config);
    }

    public function getJobs() {
        return $this->jobs;
    }

    public function setJobs(array $jobs) {
        $this->jobs = $jobs;
    }

}
