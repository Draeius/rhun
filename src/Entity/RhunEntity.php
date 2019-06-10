<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\MappedSuperclass;

/**
 * Basisklasse von allen Entities.
 *
 * @author Draeius
 * @MappedSuperclass
 */
abstract class RhunEntity {

    /**
     * The unique auto incremented primary key.
     *
     * @var int|null
     *
     * @Id
     * @Column(type="integer", options={"unsigned": true})
     * @GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    public function getId(): ?int {
        return $this->id;
    }

}
