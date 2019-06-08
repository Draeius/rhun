<?php

namespace App\Entity;

use AppBundle\Entity\Character;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of Guild
 *
 * @author Draeius
 * @Entity
 * @Table(name="guilds")
 */
class Guild {

    /**
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * 
     * @var Character
     * @OneToOne(targetEntity="AppBundle\Entity\Character")
     * @JoinColumn(name="master_id", referencedColumnName="id")
     */
    protected $master;

    /**
     *
     * @var Character[]
     * @OneToMany(targetEntity="AppBundle\Entity\Character", mappedBy="guild", fetch="EXTRA_LAZY")
     */
    protected $members;

    /**
     * 
     * @var GuildHall
     * @OneToOne(targetEntity="GuildHall", cascade={"remove"}, fetch="EXTRA_LAZY")
     * @JoinColumn(name="guild_hall_id", referencedColumnName="id", unique=true)
     */
    protected $guildHall;

    /**
     * 
     * @var string 
     * @Column(type="string", length=128)
     */
    protected $name;

    /**
     * 
     * @var string 
     * @Column(type="string", length=12)
     */
    protected $tag;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $level = 1;

    /**
     *
     * @var GuildBuff[]
     * @OneToMany(targetEntity="GuildBuff", mappedBy="guild", cascade={"remove"}, fetch="EXTRA_LAZY")
     */
    protected $buffs;

    /**
     * 
     * @var string 
     * @Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * 
     * @var string 
     * @Column(type="string", length=128, nullable=true)
     */
    protected $avatar;

    /**
     * 
     * @var string 
     * @Column(type="text", nullable=true)
     */
    protected $script;

    /**
     * 
     * @var GuildProjectItem[]
     * @OneToMany(targetEntity="GuildProject", mappedBy="guild", cascade={"remove"}, fetch="EXTRA_LAZY")
     */
    protected $projects;

    public function __construct() {
        $this->members = new ArrayCollection();
        $this->buffs = new ArrayCollection();
        $this->projects = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getMaster(): Character {
        return $this->master;
    }

    public function getMembers() {
        return $this->members;
    }

    public function addMember(Character &$character) {
        if ($this->hasMember($character)) {
            return;
        }
        $this->members[] = $character;
        $character->setGuild($this);
    }

    public function hasMember(Character $character) {
        foreach ($this->members as $member) {
            if ($member->getId() == $character->getId()) {
                return true;
            }
        }
        return false;
    }

    public function getGuildHall() {
        return $this->guildHall;
    }

    public function getLevel() {
        return $this->level;
    }

    public function getBuffs() {
        return $this->buffs;
    }

    public function getName() {
        return $this->name;
    }

    public function getTag() {
        return $this->tag;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getScript() {
        return $this->script;
    }

    public function getAvatar() {
        return $this->avatar;
    }

    public function getProjects() {
        return $this->projects;
    }

    public function setProjects($projects) {
        $this->projects = $projects;
    }

    public function setAvatar($avatar) {
        $this->avatar = $avatar;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setTag($tag) {
        $this->tag = $tag;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setScript($script) {
        $this->script = $script;
    }

    public function setMaster(Character $master) {
        $this->master = $master;
    }

    public function setMembers($members) {
        $this->members = $members;
    }

    public function setGuildHall($guildHall) {
        $this->guildHall = $guildHall;
    }

    public function setLevel($level) {
        $this->level = $level;
    }

    public function setBuffs($buffs) {
        $this->buffs = $buffs;
    }

}
