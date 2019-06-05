<?php

namespace App\Entity\Partial\Interfaces;

use App\Entity\ColoredName;
use App\Entity\Title;

/**
 * Ein Interface, das Methoden zum Erhalt des Namens eines Charakters bereitstellt.
 * 
 * @author Draeius
 */
interface CharacterNameInterface {

    /**
     * Gibt den aktuell ausgewählten und eingefärbten Namen zurück.
     * Wenn kein Name ausgewählt ist, wird der Standardname verwendet.
     * 
     * @return ColoredName
     */
    public function getSelectedName(): ColoredName;

    /**
     * Gibt den aktuell ausgewählten und eingefärbten Titel zurück.
     * Wenn kein Titel ausgewählt ist, wird der Standardtitel zurückgegeben.
     * 
     * @return Title
     */
    public function getSelectedTitle(): Title;

    /**
     * Gibt den kompletten Namen des Charakters als String zurück.
     * Dabei sind Name und Titel bereits in der korrekten Reihenfolge.
     * 
     * @return string
     */
    public function getFullName(): string;
    
    public function getDisplayName(bool $useMasked): string;
}
