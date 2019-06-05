<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * Defines the user levels and which administrative rights they have.
 *
 * @author Draeius
 * @Entity
 * @Table(name="user_level")
 */
class UserRole {

    /**
     *
     * @var int The id of this level
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     *
     * @var string The name of this UserLevel. This is to simplify the identification of the user's level.
     * @Column(type="string", length=64, nullable=false)
     */
    protected $name;

    /**
     * If users with this level may review posts
     * @var bool
     * @Column(type="boolean")
     */
    protected $reviewPosts = false;

    /**
     * If users with this level may edit the world
     * @var bool
     * @Column(type="boolean")
     */
    protected $editWorld = false;

    /**
     * If users with this level may write motds
     * @var bool
     * @Column(type="boolean")
     */
    protected $writeMotd = false;

    /**
     * If users with this level may write motds
     * @var bool
     * @Column(type="boolean")
     */
    protected $editItems = false;

    /**
     * If users with this level may write motds
     * @var bool
     * @Column(type="boolean")
     */
    protected $editMonster = false;

    public function checkUserRole(UserRole $role) {
        $check = true;
        $check &= !($this->reviewPosts && !$role->getReviewPosts());
        $check &= !($this->editWorld && !$role->getEditWorld());
        $check &= !($this->writeMotd && !$role->getWriteMotd());
        $check &= !($this->editItems && !$role->getEditItems());
        $check &= !($this->editMonster && !$role->getEditMonster());
        return $check;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getReviewPosts() {
        return $this->reviewPosts;
    }

    public function getReviewAvatars() {
        return $this->reviewAvatars;
    }

    public function getEditWorld() {
        return $this->editWorld;
    }

    public function getWriteMotd() {
        return $this->writeMotd;
    }

    public function getEditItems() {
        return $this->editItems;
    }

    public function getEditMonster() {
        return $this->editMonster;
    }

    public function setEditItems($editItems) {
        $this->editItems = $editItems;
    }

    public function setEditMonster($editMonster) {
        $this->editMonster = $editMonster;
    }

    public function setWriteMotd($writeMotd) {
        $this->writeMotd = $writeMotd;
    }

    public function setEditWorld($editWorld) {
        $this->editWorld = $editWorld;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setReviewPosts($reviewPosts) {
        $this->reviewPosts = $reviewPosts;
    }

    public function setReviewAvatars($reviewAvatars) {
        $this->reviewAvatars = $reviewAvatars;
    }

}
