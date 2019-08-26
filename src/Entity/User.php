<?php

namespace App\Entity;

use App\Service\DateTimeService;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Serializable;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * PDO of an Account
 *
 * @author Draeius
 * @Entity
 * @Table(name="accounts")
 */
class User extends RhunEntity implements UserInterface, Serializable {

    /**
     * @var string This account's name
     * @Column(type="string", length=64, unique=true, nullable=false)
     */
    protected $username = '';

    /**
     *
     * @var string The account's password
     * @Column(type="string", length=64, nullable=false)
     */
    protected $password = '';

    /**
     * This accounts email address
     * @var string
     * @Column(type="string", length=128, nullable=false, unique=true)
     */
    protected $email = '';

    /**
     * 
     * @var int gems stored on this account 
     * @Column(type="integer")
     */
    protected $gems = 0;

    /**
     *
     * @var int max amount of characters this account may have
     * @Column(type="integer")
     */
    protected $maxChars = 3;

    /**
     *
     * @var int The level of this user.
     * The higher the level the more administrative rights the user has. 
     * 
     * @ManyToOne(targetEntity="UserRole")
     * @JoinColumn(name="user_level_id", referencedColumnName="id")
     */
    protected $userRole;

    /**
     *
     * @var string the validation code used for this accounts email validation
     * @Column(type="string", length=6, nullable=false)
     */
    protected $validationCode = '';

    /**
     *
     * @var bool if this account is already validated 
     * @Column(type="boolean")
     */
    protected $validated = false;

    /**
     * The birthday of the owner of this account
     * @var DateTime
     * @Column(type="datetime", nullable=true)
     */
    protected $birthday;

    /**
     *
     * @var DateTime 
     * @Column(type="datetime", nullable=true)
     */
    protected $ban;

    /**
     *
     * @var string
     * @Column(type="text", nullable=true)
     */
    protected $banReason = null;

    /**
     * @var string 
     * @Column(type="string", length=64, nullable=true)
     */
    protected $template = "";

    /**
     * @var string 
     * @Column(type="boolean", nullable=false)
     */
    protected $acceptTerms = false;

    public function __construct() {
        $this->characters = new ArrayCollection();
        $this->birthday = new DateTime('NOW');
    }

    public function getPassword() {
        return $this->password;
    }

    public function eraseCredentials() {
        
    }

    public function getRoles() {
        return null;
    }

    public function getSalt() {
        return null;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function hasRole(UserRole $role) {
        $role->checkUserRole($this->userRole);
    }

    public function isBanned() {
        if ($this->ban == null) {
            return false;
        }
        return $this->ban > DateTimeService::getDateTime('now');
    }

    public function isAdult() {
        if (!$this->birthday) {
            return false;
        }
        return time() > strtotime('+18 years', $this->birthday->getTimestamp());
    }

    function getEmail() {
        return $this->email;
    }

    function getGems() {
        return $this->gems;
    }

    function getMaxChars() {
        return $this->maxChars;
    }

    function getValidationCode() {
        return $this->validationCode;
    }

    function getValidated() {
        return $this->validated;
    }

    function getBirthday() {
        return $this->birthday;
    }

    function getUserRole() {
        return $this->userRole;
    }

    public function getBan(): DateTime {
        return $this->ban;
    }

    public function getBanReason() {
        return $this->banReason;
    }

    public function getAcceptTerms() {
        return $this->acceptTerms;
    }

    public function setAcceptTerms($acceptTerms) {
        $this->acceptTerms = $acceptTerms;
    }

    public function addGems(int $amount) {
        $this->gems += $amount;
    }

    function setUserRole($userRole) {
        $this->userRole = $userRole;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setGems($gems) {
        $this->gems = $gems;
    }

    function setMaxChars($maxChars) {
        $this->maxChars = $maxChars;
    }

    function setValidationCode($validationCode) {
        $this->validationCode = $validationCode;
    }

    function setValidated($validated) {
        $this->validated = $validated;
    }

    function setBirthday(DateTime $birthday) {
        $this->birthday = $birthday;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    public function setBan(DateTime $ban) {
        $this->ban = $ban;
    }

    public function setBanReason($banReason) {
        $this->banReason = $banReason;
    }

    public function serialize() {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password
        ));
    }

    public function unserialize($serialized) {
        list (
                $this->id,
                $this->username,
                $this->password
                ) = unserialize($serialized);
    }

    public function getTemplate() {
        return $this->template;
    }

    public function setTemplate($template) {
        $this->template = $template;
    }

}
