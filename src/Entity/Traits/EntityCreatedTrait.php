<?php

namespace App\Entity\Traits;

use DateTime;
use Doctrine\Common\EventArgs;
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
     * Gibt an, wann das Entity kreiert wurde.
     *
     * @var DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=false, name="creation_at")
     */
    protected $createdAt;

    function getCreationDate(): ?DateTime {
        return $this->createdAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue(): void {
        $this->createdAt = new \DateTime();
    }

}
