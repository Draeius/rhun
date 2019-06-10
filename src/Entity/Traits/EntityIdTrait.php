<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @author Titouan Galopin
 */
trait EntityIdTrait {

    /**
     * The internal primary identity key.
     *
     * @var UuidInterface
     * 
     * @ORM\Column(type="uuid", unique=true)
     */
    protected $uuid;

    public function getUuid(): UuidInterface {
        return $this->uuid;
    }

    /**
     * @ORM\PrePersist
     */
    public function generateUuid() {
        $this->uuid = Uuid::uuid4();
    }

}
