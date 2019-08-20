<?php

namespace App\Entity;

use App\Entity\Character;
use App\Entity\Traits\EntityCreatedTrait;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of Bulletin
 *
 * @author Draeius
 * @Entity
 * @HasLifecycleCallbacks
 * @Table(name="bulletins")
 */
class Bulletin extends LocationBasedEntity {

    use EntityCreatedTrait;

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

    public function getAuthor(): Character {
        return $this->author;
    }

    public function getContent() {
        return $this->content;
    }

    public function setAuthor(Character $author) {
        $this->author = $author;
    }

    public function setContent($content) {
        $this->content = $content;
    }

}
