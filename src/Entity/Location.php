<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Represents a Location
 *
 * @author Draeius
 * @Entity
 * @Table(name="locations")
 */
class Location extends LocationBase {

    /**
     * The Area in which this location is set
     * @var Area
     * @ManyToOne(targetEntity="Area")
     * @JoinColumn(name="area_id", referencedColumnName="id")
     */
    protected $area;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $cityCenter = false;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $bulletin = false;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $shop = false;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $bank = false;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $library = false;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $crafting = false;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $fighting = false;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $gemShop = false;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $graveyard = false;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $guildList = false;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $houseList = false;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $jobLocation = false;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $postOffice = false;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $respawn = false;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $school = false;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $post = false;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $cs = false;

    //house location options

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $bedroom = false;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $workroom = false;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $storage = false;

    //guild location options

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $guildAdmin = false;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $guildTraining = false;

    function getArea(): Area {
        return $this->area;
    }

    function getCityCenter() {
        return $this->cityCenter;
    }

    function getShop() {
        return $this->shop;
    }

    function getBank() {
        return $this->bank;
    }

    function getLibrary() {
        return $this->library;
    }

    function getBulletin() {
        return $this->bulletin;
    }

    function getCrafting() {
        return $this->crafting;
    }

    function getDungeon() {
        return $this->dungeon;
    }

    function getFighting() {
        return $this->fighting;
    }

    function getGemShop() {
        return $this->gemShop;
    }

    function getGraveyard() {
        return $this->graveyard;
    }

    function getGuildList() {
        return $this->guildList;
    }

    function getHouseList() {
        return $this->houseList;
    }

    function getJobLocation() {
        return $this->jobLocation;
    }

    function getPostOffice() {
        return $this->postOffice;
    }

    function getRespawn() {
        return $this->respawn;
    }

    function getSchool() {
        return $this->school;
    }

    function getPost() {
        return $this->post;
    }

    function getCs() {
        return $this->cs;
    }

    function getBedroom() {
        return $this->bedroom;
    }

    function getWorkroom() {
        return $this->workroom;
    }

    function getStorage() {
        return $this->storage;
    }

    function getGuildAdmin() {
        return $this->guildAdmin;
    }

    function getGuildTraining() {
        return $this->guildTraining;
    }

    function setArea(Area $area) {
        $this->area = $area;
    }

    function setCityCenter($cityCenter) {
        $this->cityCenter = $cityCenter;
    }

    function setShop($shop) {
        $this->shop = $shop;
    }

    function setBank($bank) {
        $this->bank = $bank;
    }

    function setLibrary($library) {
        $this->library = $library;
    }

    function setBulletin($bulletin) {
        $this->bulletin = $bulletin;
    }

    function setCrafting($crafting) {
        $this->crafting = $crafting;
    }

    function setDungeon($dungeon) {
        $this->dungeon = $dungeon;
    }

    function setFighting($fighting) {
        $this->fighting = $fighting;
    }

    function setGemShop($gemShop) {
        $this->gemShop = $gemShop;
    }

    function setGraveyard($graveyard) {
        $this->graveyard = $graveyard;
    }

    function setGuildList($guildList) {
        $this->guildList = $guildList;
    }

    function setHouseList($houseList) {
        $this->houseList = $houseList;
    }

    function setJobLocation($jobLocation) {
        $this->jobLocation = $jobLocation;
    }

    function setPostOffice($postOffice) {
        $this->postOffice = $postOffice;
    }

    function setRespawn($respawn) {
        $this->respawn = $respawn;
    }

    function setSchool($school) {
        $this->school = $school;
    }

    function setPost($post) {
        $this->post = $post;
    }

    function setCs($cs) {
        $this->cs = $cs;
    }

    function setBedroom($bedroom) {
        $this->bedroom = $bedroom;
    }

    function setWorkroom($workroom) {
        $this->workroom = $workroom;
    }

    function setStorage($storage) {
        $this->storage = $storage;
    }

    function setGuildAdmin($guildAdmin) {
        $this->guildAdmin = $guildAdmin;
    }

    function setGuildTraining($guildTraining) {
        $this->guildTraining = $guildTraining;
    }

    public function getDataArray(): array {
        $fields = parent::getDataArray();
        unset($fields['area']);
        return $fields;
    }

    protected function getClassName(): string {
        return 'App\Entity\Location';
    }

}
