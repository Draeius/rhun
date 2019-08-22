<?php

namespace App\Util\Config;

use App\Util\Price;

/**
 * Enthält die Konfiguration für neue Charaktere.
 *
 * @author Draeius
 */
class Settings extends Config {

    public function getStartMoney(): Price {
        return $this->getPrice('start_money');
    }

    public function getStartUserLevelId(): int {
        return $this->getData('start_user_level');
    }

    public function getNeedEmailValidation(): bool {
        return $this->getData('email_validation');
    }

    public function getPhasesPerDay(): int {
        return $this->getData('phase_per_day');
    }

    /**
     * Gibt zurück wie lange der OOC aufgehoben wird
     * @return int
     */
    public function getOocDays(): int {
        return $this->getData('ooc_days');
    }

    /**
     * Gibt zurück wie lange Posts aufgehoben werden
     * @return int
     */
    public function getPostDays(): int {
        return $this->getData('post_days');
    }

    public function getDeleteThreshold(): int {
        return $this->getData('delete_threshold');
    }

    public function getActionPoints(): int {
        return $this->getData('action_points');
    }

}
