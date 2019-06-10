<?php

namespace App\Entity;

use App\Entity\Traits\EntityCreatedTrait;
use DateTime;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of ShortNews
 *
 * @author Draeius
 * @Entity
 * @Table(name="short_news")
 * @HasLifecycleCallbacks
 */
class ShortNews extends RhunEntity {

    use EntityCreatedTrait;

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

    public function getCharacter(): Character {
        return $this->character;
    }

    public function getContent() {
        return $this->content;
    }

    public function setCharacter(Character $character) {
        $this->character = $character;
    }

    public function setContent($content) {
        $this->content = $content;
    }

}
