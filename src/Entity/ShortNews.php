<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of ShortNews
 *
 * @author Draeius
 * @Entity
 * @Table(name="short_news")
 */
class ShortNews {

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
     * @var DateTime
     * @Column(type="datetime")
     */
    protected $created;

    /**
     * @var Character
     * @ManyToOne(targetEntity="Character")
     * @JoinColumn(name="author_id", referencedColumnName="id")
     */
    protected $character;

    /**
     * The content of this post
     * @var string
     * @Column(type="text")
     */
    protected $content;

    public function getId() {
        return $this->id;
    }

    public function getCreated(): DateTime {
        return $this->created;
    }

    public function getCharacter(): Character {
        return $this->character;
    }

    public function getContent() {
        return $this->content;
    }

    public function setCreated(DateTime $created) {
        $this->created = $created;
    }

    public function setCharacter(Character $character) {
        $this->character = $character;
    }

    public function setContent($content) {
        $this->content = $content;
    }

}
