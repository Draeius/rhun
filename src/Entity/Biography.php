<?php

namespace App\Entity;

use App\Entity\Traits\EntityOwnerTrait;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * A character's biography
 *
 * @author Draeius
 * @Entity
 * @Table(name="biographies")
 */
class Biography extends RhunEntity {

    use EntityOwnerTrait;

    /**
     *
     * @var Character
     * @ManyToOne(targetEntity="Character", inversedBy="biographies")
     */
    protected $owner;

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

    /**
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $features = '';

    /**
     * @var string
     * @Column(type="string", length=128, nullable=true)
     */
    protected $interests = '';

    /**
     * @var string
     * @Column(type="string", length=64, nullable=true)
     */
    protected $lastName = '';

    /**
     * @var string
     * @Column(type="string", length=64, nullable=true)
     */
    protected $origin = '';

    /**
     * @var string
     * @Column(type="string", length=64, nullable=true)
     */
    protected $skinColor = '';

    /**
     * @var string
     * @Column(type="string", length=64, nullable=true)
     */
    protected $dislikes = '';

    /**
     * @var string
     * @Column(type="string", length=64, nullable=true)
     */
    protected $weaknesses = '';

    /**
     * @var string
     * @Column(type="string", length=64, nullable=true)
     */
    protected $skills = '';

    /**
     * @var string
     * @Column(type="string", length=64, nullable=true)
     */
    protected $parents = '';

    /**
     * @var string
     * @Column(type="string", length=64, nullable=true)
     */
    protected $siblings = '';

    /**
     * @var string
     * @Column(type="string", length=64, nullable=true)
     */
    protected $children = '';

    /**
     * @var string
     * @Column(type="string", length=64, nullable=true)
     */
    protected $spouse = '';

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

    function getFeatures() {
        return $this->features;
    }

    function getInterests() {
        return $this->interests;
    }

    function getLastName() {
        return $this->lastName;
    }

    function getOrigin() {
        return $this->origin;
    }

    function getSkinColor() {
        return $this->skinColor;
    }

    function getDislikes() {
        return $this->dislikes;
    }

    function getWeaknesses() {
        return $this->weaknesses;
    }

    function getSkills() {
        return $this->skills;
    }

    function getParents() {
        return $this->parents;
    }

    function getSiblings() {
        return $this->siblings;
    }

    function getChildren() {
        return $this->children;
    }

    function getSpouse() {
        return $this->spouse;
    }

    function setFeatures($features) {
        $this->features = $features;
    }

    function setInterests($interests) {
        $this->interests = $interests;
    }

    function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    function setOrigin($origin) {
        $this->origin = $origin;
    }

    function setSkinColor($skinColor) {
        $this->skinColor = $skinColor;
    }

    function setDislikes($dislikes) {
        $this->dislikes = $dislikes;
    }

    function setWeaknesses($weaknesses) {
        $this->weaknesses = $weaknesses;
    }

    function setSkills($skills) {
        $this->skills = $skills;
    }

    function setParents($parents) {
        $this->parents = $parents;
    }

    function setSiblings($siblings) {
        $this->siblings = $siblings;
    }

    function setChildren($children) {
        $this->children = $children;
    }

    function setSpouse($spouse) {
        $this->spouse = $spouse;
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
