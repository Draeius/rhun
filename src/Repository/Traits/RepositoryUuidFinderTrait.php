<?php

namespace App\Repository\Traits;

use App\Doctrine\UuidEncoder;

/**
 * Ein Trait, das dafür sorgt, dass Repositories Entities mithilfe einer in base32 codierten Uuid finden können.
 * Die Variable $uuidEncoder muss im entsprechenden Repository initialisiert werden.
 *
 * @author Titouan Galopin
 */
trait RepositoryUuidFinderTrait {

    /**
     * @var UuidEncoder
     */
    protected $uuidEncoder;

    public function findOneByEncodedUuid(string $encodedUuid) {
        return $this->findOneBy([
                    'uuid' => $this->uuidEncoder->decode($encodedUuid)
        ]);
    }

}
