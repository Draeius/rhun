<?php

namespace App\Entity;

use App\Entity\Traits\EntityOwnerTrait;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of DiaryEntiry
 *
 * @author Draeius
 * @Entity
 * @Table(name="diary_entries")
 */
class DiaryEntry extends RhunEntity {

    use EntityOwnerTrait;

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

    public function getTitle() {
        return $this->title;
    }

    public function getScript() {
        return $this->script;
    }

    public function getText() {
        return $this->text;
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
