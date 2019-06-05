<?php

namespace App\Service;

/**
 * Description of EmailValidationService
 *
 * @author Draeius
 */
class ValidationService {

    /**
     * Generiert einen 6-Stelligen Validierungscode um Emails zu Validieren.
     * @return string
     */
    public function getValidationCode(): string {
        $continue = true;
        do {
            $rand = substr(md5(microtime()), rand(0, 26), 6);
            if (!is_numeric($rand)) {
                $continue = false;
            }
        } while ($continue);
        return $rand;
    }

}
