<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * A Format that is used to format text.
 *
 * @author Draeius
 * @Entity(readOnly=true)
 * @Table(name="formats")
 */
class Format {

    /**
     * The id of this format
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * The code for this format
     * @var string
     * @Column(type="string", length=2, nullable=false)
     */
    protected $code;

    /**
     * The color that this format represents
     * @var string
     * @Column(type="string", length=6)
     */
    protected $color;

    /**
     * The Html tag that is used with this format
     * @var string
     * @Column(type="string", length=15)
     */
    protected $tag;

    /**
     * The style that is added to the html tag
     * @var string
     * @Column(type="string")
     */
    protected $style;

    /**
     * If this format is allowed for user
     * @var bool
     * @Column(type="boolean") 
     */
    protected $isAllowed;

    /**
     * The id of this format
     * @var int
     * @Column(type="integer", options={"default" : 0})
     */
    protected $displayList = 0;

    public function getId() {
        return $this->id;
    }

    public function getCode() {
        return $this->code;
    }

    public function getColor() {
        return $this->color;
    }

    public function getTag() {
        return $this->tag;
    }

    public function getStyle() {
        return $this->style;
    }

    public function getIsAllowed() {
        return $this->isAllowed;
    }

    public function getDisplayList() {
        return $this->displayList;
    }

    public function setDisplayList($displayList) {
        $this->displayList = $displayList;
    }

    public function setIsAllowed($allowed) {
        $this->isAllowed = $allowed;
    }

    public function setCode($code) {
        $this->code = $code;
    }

    public function setColor($color) {
        $this->color = $color;
    }

    public function setTag($tag) {
        $this->tag = $tag;
    }

    public function setStyle($style) {
        $this->style = $style;
    }

}
