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
 * Description of Book
 *
 * @author Draeius
 * @Entity
 * @Table(name="books")
 */
class Book {

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
     * @ManyToOne(targetEntity="Character")
     * @JoinColumn(name="author_id", referencedColumnName="id", nullable=true)
     */
    protected $author;

    /**
     *
     * @var BookTheme
     * @ManyToOne(targetEntity="BookTheme", inversedBy="books")
     * @JoinColumn(name="theme_id", referencedColumnName="id")
     */
    protected $theme;

    /**
     *
     * @var string
     * @Column(type="text", length=64)
     * @Assert\Length(
     *      max = 64,
     *      maxMessage = "Der {{ value }}  darf nicht länger als {{ limit }} Zeichen sein."
     * )
     */
    protected $title;

    /**
     *
     * @var string
     * @Column(type="text")
     * @Assert\NotBlank(message="Du willst ein leeres Buch schreiben? Sowas will doch Keiner lesen.")
     */
    protected $content;

    /**
     *
     * @var boolean
     * @Column(type="boolean")
     */
    protected $activated = true;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $listOrder = 0;

    public function getId() {
        return $this->id;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function getTheme() {
        return $this->theme;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getContent() {
        return $this->content;
    }

    public function getActivated() {
        return $this->activated;
    }

    public function getListOrder() {
        return $this->listOrder;
    }

    public function setAuthor(Character $author) {
        $this->author = $author;
    }

    public function setTheme(BookTheme $theme) {
        $this->theme = $theme;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setActivated($activated) {
        $this->activated = $activated;
    }

    public function setListOrder($listOrder) {
        $this->listOrder = $listOrder;
    }

}
