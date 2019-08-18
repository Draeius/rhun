<?php

namespace App\Util\Session;

use App\Entity\Character;
use App\Util\TabIdentification\NoTabIdentifierSetException;
use App\Util\TabIdentification\TabIdentifier;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Enthält wichtige Methoden zur verwaltung der Session.
 *
 * @author Draeius
 */
class RhunSession extends Session {

    /**
     *
     * @var TabIdentifier
     */
    private static $tabIdentifier;

    function getTabIdentifier(): TabIdentifier {
        return self::$tabIdentifier;
    }

    public static function SET_TAB_IDENTIFIER(TabIdentifier $tabIdentifier) {
        self::$tabIdentifier = $tabIdentifier;
    }

    public function getAccountID(): ?int {
        return $this->get('account');
    }

    public function setAccountID(int $accountID): void {
        $this->set('account', $accountID);
    }

    public function deleteAccountID(): void {
        $this->remove('account');
    }

    public function getCharacterID(): ?int {
        return $this->getFromArray('character');
    }

    public function setCharacterID(int $characterID): void {
        $this->setInArray('character', $characterID);
    }

    public function deleteCharacterID(): void {
        $this->deleteFromArray('character');
    }

    public function setFightData(array $fightData): void {
        $this->setInArray('fight', $fightData);
    }

    public function getFightData() {
        return $this->getFromArray('fight');
    }

    public function deleteFightData(): void {
        $this->deleteFromArray('fight');
    }

    public function addToFightLog($message) {
        $oldLog = $this->getFromArray('fight_log');
        $oldLog[] = $message;
        $this->setInArray('fight_log', $oldLog);
    }

    public function getFightLog() {
        return $this->getFromArray('fight_log');
    }

    public function deleteFightLog() {
        $this->deleteFromArray('fight_log');
    }

    public function setBookTheme($theme) {
        if ($theme == null || $theme == 0) {
            return $this->error('Null as Theme');
        }
        $this->setInArray('bookTheme', $theme);
    }

    public function getBookTheme() {
        return $this->getFromArray('bookTheme');
    }

    public function setBreakIn($breakIn) {
        if ($breakIn) {
            $this->setInArray('breakIn', 'true');
        } else {
            $this->deleteFromArray('breakIn');
        }
    }

    public function getBreakIn() {
        return $this->getFromArray('breakIn');
    }

    /**
     * Kontrolliert, ob die gegebene id bereits als TabIdentifier genutzt wird.
     * 
     * @param string $id
     */
    public function isUsedID($id): bool {
        return $this->get($id) !== null;
    }

    public function error(string $error): void {
        $oldError = $this->getFromArray('error');
        if (!$oldError) {
            $oldError = '';
        }
        $this->setInArray('error', $oldError . '<br />' . $error);
    }

    public function getError(bool $clear = true): string {
        $error = $this->getFromArray('error');
        if ($clear) {
            $this->deleteFromArray('error');
        }
        return $error ? $error : '';
    }

    public function log(string $log): void {
        $oldLog = $this->getFromArray('log');
        if (!$oldLog) {
            $oldLog = '';
        }
        $this->setInArray('log', $oldLog . '<br />' . $log);
    }

    public function getLog(bool $clear = true): string {
        $log = $this->getFromArray('log');
        if ($clear) {
            $this->deleteFromArray('log');
        }
        return $log ? $log : '';
    }

    public function getMonsterCount(): int {
        return $this->getFromArray('monsterCount');
    }

    public function setMonsterCount(int $count): void {
        $this->setInArray('monsterCount', $count);
    }

    public function deleteMonsterCount(): void {
        $this->deleteFromArray('monsterCount');
    }

    public function clearData(): void {
        $this->deleteAccountID();
        if ($this->getTabIdentifier()->getIdentifier()) {
            $this->set($this->getTabIdentifier()->getIdentifier(), array());
        }
    }

    public function clearDataForChar(Character $char): void {
        $iterator = $this->getIterator();
        while ($iterator->valid()) {
            $key = $iterator->key();
            if (is_array($this->get($key)) && array_key_exists('character', $this->get($key)) &&
                    $this->get($key)['character'] == $char->getId()) {
                $this->set($key, array());
            }
            $iterator->next();
        }
    }

    private function deleteFromArray($key): void {
        if (!$this->getTabIdentifier()->hasIdentifier()) {
            throw new NoTabIdentifierSetException('No TabIdentifier set in Session.');
        }
        if (!is_array($this->get($this->getTabIdentifier()->getIdentifier()))) {
            return;
        }
        $array = $this->get($this->getTabIdentifier()->getIdentifier());
        unset($array[$key]);
        $this->set($this->getTabIdentifier()->getIdentifier(), $array);
    }

    private function setInArray($key, $value): void {
        if ($this->getTabIdentifier()->hasIdentifier()) {
            if (!is_array($this->get($this->getTabIdentifier()->getIdentifier()))) {
                $this->set($this->getTabIdentifier()->getIdentifier(), array());
            }
            $array = $this->get($this->getTabIdentifier()->getIdentifier());
            $array[$key] = $value;
            $this->set($this->getTabIdentifier()->getIdentifier(), $array);
            return;
        }
        throw new NoTabIdentifierSetException('No TabIdentifier set in Session.');
    }

    private function getFromArray($key): ?string {
        if ($this->getTabIdentifier()->hasIdentifier()) {
            if (!is_array($this->get($this->getTabIdentifier()->getIdentifier()))) {
                $this->set($this->getTabIdentifier()->getIdentifier(), array());
            }
            try {
                return $this->get($this->getTabIdentifier()->getIdentifier())[$key];
            } catch (\Exception $ex) {
                return null;
            }
        }
        throw new NoTabIdentifierSetException('No TabIdentifier set in Session.');
    }

}
