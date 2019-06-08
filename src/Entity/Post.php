<?php

namespace App\Entity;

use App\Service\DateTimeService;
use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use App\Entity\Location\LocationEntity;

/**
 * Description of Post
 *
 * @author Matthias
 * @Entity(repositoryClass="App\Repository\PostRepository")
 * @Table(name="posts")
 */
class Post {

    /**
     * The post's id
     * @var int 
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * The author of this post
     * @var Character
     * @ManyToOne(targetEntity="Character")
     * @JoinColumn(name="author_id", referencedColumnName="id")
     */
    protected $author;

    /**
     * The content of this post
     * @var string
     * @Column(type="text")
     */
    protected $content;

    /**
     * The target of this nav
     * @var PostableLocationEntity
     * @ManyToOne(targetEntity="App\Entity\Location\PostableLocationEntity", inversedBy="posts")
     * @JoinColumn(name="target_location_id", referencedColumnName="id")
     */
    protected $location;

    /**
     * The date and time at which this post was created
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

    public function getAuthor() {
        return $this->author;
    }

    public function getContent() {
        return $this->content;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getLocation() {
        return $this->location;
    }

    public function setLocation(LocationEntity $location) {
        $this->location = $location;
    }

    public function setAuthor(Character $author) {
        $this->author = $author;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setCreationDate(DateTime $creationDate) {
        $this->creationDate = $creationDate;
    }

}
