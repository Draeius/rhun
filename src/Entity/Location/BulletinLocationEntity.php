<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity\Location;

use AppBundle\Entity\Bulletin;
use AppBundle\Util\Config\Config;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use NavigationBundle\Location\BulletinLocation;

/**
 * Description of BulletinLocationEntity
 *
 * @Entity
 * @Table(name="location_bulletin")
 */
class BulletinLocationEntity extends PostableLocationEntity {

    /**
     *
     * @var Bulletin[]
     * @OneToMany(targetEntity="AppBundle\Entity\Bulletin", mappedBy="location")
     */
    protected $bulletins;

    public function __construct() {
        parent::__construct();

        $this->bulletins = new ArrayCollection();
    }

    public function getLocationInstance(EntityManager $manager, string $uuid, Config $config) {
        return new BulletinLocation($manager, $uuid, $this, $config);
    }

    public function getTemplate() {
        return 'locations/bulletinLocation';
    }

    public function getBulletins() {
        return $this->bulletins;
    }

    public function setBulletins($bulletins) {
        $this->bulletins = $bulletins;
    }

    public function addBulletin($bulletin) {
        $this->bulletins[] = $bulletin;
    }

}
