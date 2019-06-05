<?php

namespace App\Query;

use Exception;

/**
 * Die Exception wird geworfen, wenn das Resultat einer SQL Anfrage zu viele Zeilen hat.
 * Kann auf eine Inkonsistenz in der Datenbank hinweisen. 
 *
 * @author Draeius
 */
class TooManyRowsException extends Exception {
    //put your code here
}
