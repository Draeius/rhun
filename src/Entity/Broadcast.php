<?php

namespace App\Entity;

use App\Entity\Traits\EntityCreatedTrait;
use App\Entity\Traits\EntityIdTrait;
use App\Entity\Traits\EntityIsNewestTrait;
use App\Repository\BroadcastRepository;
use App\Service\DateTimeService;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Table;

/**
 * Represents a message that is distributed to all accounts by the admins
 * 
 * @author Draeius
 * @Entity(repositoryClass="BroadcastRepository")
 * @HasLifecycleCallbacks
 * @Table(name="broadcasts")
 */
class Broadcast extends RhunEntity {

    use EntityIdTrait;
    use EntityCreatedTrait;
    use EntityIsNewestTrait;

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

    public function getContent() {
        return $this->content;
    }

    public function getCodingBroadcast() {
        return $this->codingBroadcast;
    }

    public function setCodingBroadcast($codingBroadcast) {
        $this->codingBroadcast = $codingBroadcast;
    }

    public function setContent($content) {
        $this->content = $content;
    }

}
