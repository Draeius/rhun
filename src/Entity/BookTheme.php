<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of BookTheme
 *
 * @author Draeius
 * @Entity
 * @Table(name="book_themes")
 */
class BookTheme extends RhunEntity {

    /**
     *
     * @var string
     * @Column(type="string", length=64)
     */
    protected $theme;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $listOrder;

    /**
     *
     * @var Book[]
     * @OneToMany(targetEntity="Book", mappedBy="theme", fetch="EXTRA_LAZY")
     */
    protected $books;

    public function getTheme() {
        return $this->theme;
    }

    public function getListOrder() {
        return $this->listOrder;
    }

    /**
     * 
     * @return Book[]
     */
    public function getBooks() {
        return $this->books;
    }

    public function setBooks(array $books) {
        $this->books = $books;
    }

    public function setTheme($theme) {
        $this->theme = $theme;
    }

    public function setListOrder($listOrder) {
        $this->listOrder = $listOrder;
    }

}
