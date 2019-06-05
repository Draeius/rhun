<?php

namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Ein Trait, das ein created Feld enthält und es ermöglicht, nach dem creationDate zu sortieren.
 * Das creationDate muss im entsprechenden Constructor gesetzt werden.
 * 
 * <b>Die Spalte "creation_at" sollte als index gesetzt werden.<br />
 * Muss die Annotation ORM\HasLifecycleCallbacks haben.</b>
 *
 * @author Draeius
 */
trait EntityCreatedTrait {

    /**
     * The internal primary identity key.
     *
     * @var DateTime|null
     *
     * @ORM\Column(type="dateTime", nullable=false, name="creation_at")
     */
    protected $createdAt;

    /**
     * The internal primary identity key.
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false, name="is_current")
     */
    protected $current = false;

    function getCreationDate(): ?DateTime {
        return $this->createdAt;
    }

    function getCurrent(): bool {
        return $this->current;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue(): void {
        $this->createdAt = new \DateTime();
    }

}
