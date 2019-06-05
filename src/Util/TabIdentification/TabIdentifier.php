<?php

namespace App\Util\TabIdentification;

/**
 * Der TabIdentifier wird dazu genutzt, die unterschiedlichen Tabs eines Browsers auseinander zu halten.
 *
 * @author Draeius
 */
class TabIdentifier {

    /**
     * Dieser String wird dazu genutzt die Tabs eindeutig zu identifizieren.
     * 
     * @var string|null
     */
    private $identifier;

    public function __construct(?string $identifier) {
        $this->identifier = $identifier;
    }

    /**
     * Gibt den identifier der aktuell verwendet wird zurück.
     * 
     * @return string Wenn kein Identifier benutzt wird, wird standardmäßig "0000" zurückgegeben.
     */
    public function getIdentifier(): ?string {
        if ($this->identifier !== null) {
            return $this->identifier;
        }
        return '0000';
    }

    public function setIdentifier(?string $identifier): void {
        if (!$this->isValid($identifier)) {
            throw new InvalidTabIdentifierException($identifier);
        }
        $this->identifier = $identifier;
    }

    /**
     * Gibt an, ob der gegebene Identifier valid ist oder nicht.
     * @param string $test
     * @return bool
     */
    public function isValid(?string $test): bool {
        if ($test !== null) {
            return strlen($test) == 4;
        }
        return true;
    }

    /**
     * Gibt an, ob dieser Identifier aktuell einen Tab Identifiziert.
     * 
     * @return bool
     */
    public function hasIdentifier(): bool {
        return $this->identifier !== null;
    }

    public function __toString() {
        if ($this->identifier === null) {
            return '';
        }
        return $this->identifier;
    }

}
