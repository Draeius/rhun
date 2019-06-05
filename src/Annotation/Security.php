<?php

namespace App\Annotation;

use App\Entity\UserRole;
use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 */
class Security {

    /**
     * @var bool
     */
    public $needAccount = false;

    /**
     * @var bool
     */
    public $needCharacter = false;

    /**
     *
     * @var bool
     */
    public $reviewPosts = false;

    /**
     * If users with this level may edit the world
     * @var bool
     */
    public $editWorld = false;

    /**
     * If users with this level may write motds
     * @var bool
     */
    public $writeMotd = false;

    /**
     * 
     * @var bool
     */
    public $editItems = false;

    /**
     * @var bool
     */
    public $editMonster = false;

    public function getNeedAccount() {
        return $this->needAccount;
    }

    public function getNeedCharacter() {
        return $this->needCharacter;
    }

    public function getUserRole(): UserRole {
        $role = new UserRole();
        $role->setEditItems($this->editItems);
        $role->setEditMonster($this->editMonster);
        $role->setEditWorld($this->editWorld);
        $role->setReviewPosts($this->reviewPosts);
        $role->setWriteMotd($this->writeMotd);
        return $role;
    }

    public function getReviewPosts() {
        return $this->reviewPosts;
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

}
