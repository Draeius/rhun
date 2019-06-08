<?php

namespace App\Entity;

use App\Entity\Character;
use App\Service\DateTimeService;
use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use App\Entity\Location\BulletinLocationEntity;
use App\Entity\Location\PostableLocationEntity;

/**
 * Description of Bulletin
 *
 * @author Matthias
 * @Entity
 * @Table(name="bulletin")
 */
class Bulletin {

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
     * @JoinColumn(name="author_id", referencedColumnName="id")
     */
    protected $author;

    /**
     * 
     * @var string
     * @Column(type="text")
     */
    protected $content;

    /**
     * 
     * @var BulletinLocationEntity
     * @ManyToOne(targetEntity="App\Entity\Location\BulletinLocationEntity", inversedBy="bulletins")
     * @JoinColumn(name="location_id", referencedColumnName="id")
     */
    protected $location;

    /**
     * 
     * @var DateTime
     * @Column(type="datetime")
     */
    protected $creationDate;

    public function __construct() {
        $this->creationDate = DateTimeService::getDateTime("now");
    }

    public function getId() {
        return $this->id;
    }

    public function getAuthor(): Character {
        return $this->author;
    }

    public function getContent() {
        return $this->content;
    }

    public function getLocation(): PostableLocationEntity {
        return $this->location;
    }

    public function getCreationDate(): DateTime {
        return $this->creationDate;
    }

    public function setAuthor(Character $author) {
        $this->author = $author;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setLocation(PostableLocationEntity $location) {
        $this->location = $location;
    }

    public function setCreationDate(DateTime $creationDate) {
        $this->creationDate = $creationDate;
    }

}
