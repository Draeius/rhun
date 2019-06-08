<?php

namespace App\Entity;

use App\Entity\Character;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * A character's biography
 *
 * @author Draeius
 * @Entity
 * @Table(name="biographies")
 */
class Biography {

    /**
     * The biographie's id
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * The text name of this biography
     * @var string
     * @Column(type="text", length=64)
     */
    protected $name;

    /**
     * The name that is shown if this bio is activated.
     * @var string
     * @Column(type="text", length=128, nullable=true)
     */
    protected $alternateCharName;

    /**
     * The owner of this biography
     * @var Character 
     * @ManyToOne(targetEntity="Character", inversedBy="biography")
     * @JoinColumn(name="character_id", referencedColumnName="id")
     */
    protected $owner;

    /**
     * The character's avatar
     * @var Avatar 
     * @Column(type="string", length=256)
     */
    protected $avatar = '';

    /**
     * The text content of this biography
     * @var string
     * @Column(type="text")
     */
    protected $text = '';

    /**
     * The javascript that this biography is using.
     * @var string 
     * @Column(type="text")
     */
    protected $script = '';

    /**
     * If the script of this biography is enabled or not
     * @var bool
     * @Column(type="boolean")
     */
    protected $scriptEnabled = true;

    /**
     * The reason why the script has been disabled
     * @var string 
     * @Column(type="string", length=255)
     */
    protected $reason = '';

    /**
     * If the script has changed since the disabling
     * @var bool
     * @Column(type="boolean") 
     */
    protected $scriptChanged = false;

    /**
     * If this biography is selected
     * @var boolean
     * @Column(type="boolean")
     */
    protected $selected = false;

    /**
     * If this biography is for masked Balls
     * @var boolean
     * @Column(type="boolean")
     */
    protected $maskedBall = false;

    /**
     *
     * @var string 
     * @Column(type="string", length=1)
     */
    protected $standardColorSpeech = '';

    /**
     *
     * @var string
     * @Column(type="string", length=1)
     */
    protected $standardColorAction = '';

    /**
     * How tall the character is
     * @var string
     * @Column(type="string", length=64, nullable=true)
     */
    protected $height;

    /**
     * The character's haircolor
     * @var string 
     * @Column(type="string", length=64, nullable=true)
     */
    protected $haircolor;

    /**
     * The character's haircut
     * @var string
     * @Column(type="string", length=64, nullable=true)
     */
    protected $haircut;

    /**
     * @var string
     * @Column(type="string", length=64, nullable=true)
     */
    protected $ethnicity;

    /**
     * @var string
     * @Column(type="string", length=64, nullable=true)
     */
    protected $age;

    /**
     * @var string
     * @Column(type="string", length=64, nullable=true)
     */
    protected $disposition;

    /**
     * @var string
     * @Column(type="string", length=64, nullable=true)
     */
    protected $eyecolor;

    /**
     * @var string
     * @Column(type="string", length=64, nullable=true)
     */
    protected $modeledAfter;

    public function getId() {
        return $this->id;
    }

    public function getText() {
        return $this->text;
    }

    public function getScript() {
        return $this->script;
    }

    public function getScriptEnabled() {
        return $this->scriptEnabled;
    }

    public function getReason() {
        return $this->reason;
    }

    public function getScriptChanged() {
        return $this->scriptChanged;
    }

    public function getOwner() {
        return $this->owner;
    }

    public function getAvatar() {
        return $this->avatar;
    }

    public function getSelected() {
        return $this->selected;
    }

    public function getName() {
        return $this->name;
    }

    public function getAlternateCharName() {
        return $this->alternateCharName;
    }

    public function getMaskedBall() {
        return $this->maskedBall;
    }

    public function getStandardColorSpeech() {
        return $this->standardColorSpeech;
    }

    public function getStandardColorAction() {
        return $this->standardColorAction;
    }

    public function getHeight() {
        return $this->height;
    }

    public function getHaircolor() {
        return $this->haircolor;
    }

    public function getHaircut() {
        return $this->haircut;
    }

    public function getEthnicity() {
        return $this->ethnicity;
    }

    public function getAge() {
        return $this->age;
    }

    public function getDisposition() {
        return $this->disposition;
    }

    public function getEyecolor() {
        return $this->eyecolor;
    }

    function getModeledAfter() {
        return $this->modeledAfter;
    }

    function setModeledAfter($modeledAfter) {
        $this->modeledAfter = $modeledAfter;
    }

    public function setHeight($height) {
        $this->height = $height;
    }

    public function setHaircolor($haircolor) {
        $this->haircolor = $haircolor;
    }

    public function setHaircut($haircut) {
        $this->haircut = $haircut;
    }

    public function setEthnicity($ethnicity) {
        $this->ethnicity = $ethnicity;
    }

    public function setAge($age) {
        $this->age = $age;
    }

    public function setDisposition($disposition) {
        $this->disposition = $disposition;
    }

    public function setEyecolor($eyecolor) {
        $this->eyecolor = $eyecolor;
    }

    public function setStandardColorSpeech($standardColorSpeech) {
        $this->standardColorSpeech = $standardColorSpeech;
    }

    public function setStandardColorAction($standardColorAction) {
        $this->standardColorAction = $standardColorAction;
    }

    public function setAlternateCharName($alternateCharName) {
        $this->alternateCharName = $alternateCharName;
    }

    public function setMaskedBall($maskedBall) {
        $this->maskedBall = $maskedBall;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setSelected($selected) {
        $this->selected = $selected;
    }

    public function setOwner(Character $owner) {
        $this->owner = $owner;
    }

    public function setAvatar($avatar) {
        $this->avatar = $avatar;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function setScript($script) {
        $this->script = $script;
    }

    public function setScriptEnabled($scriptEnabled) {
        $this->scriptEnabled = $scriptEnabled;
    }

    public function setReason($reason) {
        $this->reason = $reason;
    }

    public function setScriptChanged($scriptChanged) {
        $this->scriptChanged = $scriptChanged;
    }

}
