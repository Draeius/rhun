<?php

namespace App\Entity;

use App\Entity\Traits\EntityCreatedTrait;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * A private Message sent from one character to another
 *
 * @author Draeius
 * @Entity
 * @Entity(repositoryClass="App\Repository\MessageRepository")
 * @Table(name="messages")
 * @HasLifecycleCallbacks
 */
class Message extends RhunEntity {

    use EntityCreatedTrait;
    
    /**
     * The sender of this message
     * @var Character 
     * @ManyToOne(targetEntity="Character", inversedBy="messageSent")
     * @JoinColumn(name="sender_id", referencedColumnName="id", nullable=true)
     */
    protected $sender;

    /**
     * The addressee of this message
     * @var Character
     * @ManyToOne(targetEntity="Character", inversedBy="receivedMessages")
     * @JoinColumn(name="addressee_id", referencedColumnName="id")
     */
    protected $addressee;

    /**
     * The content of this message
     * @var string
     * @Column(type="string", length=100)
     */
    protected $subject;

    /**
     * If this message has been read
     * @var bool
     * @Column(name="is_old", type="boolean")
     */
    protected $read = false;

    /**
     * If this message has been read
     * @var bool
     * @Column(type="boolean")
     */
    protected $important = false;

    /**
     * The content of this message
     * @var string
     * @Column(type="text")
     */
    protected $content;

    public function getSender() {
        return $this->sender;
    }

    public function getAddressee() {
        return $this->addressee;
    }

    public function getRead() {
        return $this->read;
    }

    public function getCreated() {
        return $this->created;
    }

    public function getContent() {
        return $this->content;
    }

    public function getSubject() {
        return $this->subject;
    }

    public function getImportant() {
        return $this->important;
    }

    public function setImportant($important) {
        $this->important = $important;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setSender(?Character $sender) {
        $this->sender = $sender;
    }

    public function setAddressee(Character $addressee) {
        $this->addressee = $addressee;
    }

    public function setRead($read) {
        $this->read = $read;
    }

}
