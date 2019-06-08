<?php

namespace App\Entity;

use App\Service\DateTimeService;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

/**
 * Represents a message that is distributed to all accounts by the admins
 * 
 * @author Draeius
 * @Entity(repositoryClass="App\Repository\BroadcastRepository")
 * @Table(name="broadcasts")
 */
class Broadcast {

    /**
     *
     * @var int The id of this broadcast
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     *
     * @var DateTime The date this broadcast was created
     * @Column(type="datetime", name="creation_date")
     */
    protected $creationDate;

    /**
     * 
     * @var string The content of this broadcast 
     * @Column(type="text", nullable=false)
     */
    protected $content;

    /**
     *
     * @var bool 
     * @Column(type="boolean", nullable=false)
     */
    protected $codingBroadcast;

    public function __construct() {
        $this->creationDate = DateTimeService::getDateTime("NOW");
    }

    public function getId() {
        return $this->id;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getContent() {
        return $this->content;
    }

    public function getCodingBroadcast() {
        return $this->codingBroadcast;
    }

    public function setCodingBroadcast($codingBroadcast) {
        $this->codingBroadcast = $codingBroadcast;
    }

    public function setCreationDate(DateTime $creationDate) {
        $this->creationDate = $creationDate;
    }

    public function setContent($content) {
        $this->content = $content;
    }

}
