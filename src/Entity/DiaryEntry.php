<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of DiaryEntiry
 *
 * @author Draeius
 * @Entity
 * @Table(name="diary_entries")
 */
class DiaryEntry {

    /**
     * 
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * 
     * @var Character 
     * @ManyToOne(targetEntity="Character", inversedBy="diaryEntries")
     * @JoinColumn(name="owner_id", referencedColumnName="id")
     */
    protected $owner;

    /**
     * 
     * @var string
     * @Column(type="text", length=64)
     */
    protected $title;

    /**
     * 
     * @var string
     * @Column(type="text")
     */
    protected $script;

    /**
     * 
     * @var string
     * @Column(type="text")
     */
    protected $text;

    public function getId() {
        return $this->id;
    }

    public function getOwner(): Character {
        return $this->owner;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getScript() {
        return $this->script;
    }

    public function getText() {
        return $this->text;
    }

    public function setOwner(Character $owner) {
        $this->owner = $owner;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setScript($script) {
        $this->script = $script;
    }

    public function setText($text) {
        $this->text = $text;
    }

}
