<?php

namespace App\Util\TabIdentification;

use Exception;

/**
 * Eine Exception die geworfen wird, wenn der TabIdentifier nicht valid ist.
 *
 * @author Draeius
 */
class InvalidTabIdentifierException extends Exception {

    public function __construct(string $identifier) {
        parent::__construct('Der TabIdentifier "' . $identifier . '" ist nicht zulässig.');
    }

}
