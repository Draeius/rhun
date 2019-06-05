<?php

namespace App\Util\Config;

use App\Util\Price;

/**
 * Description of Config
 *
 * @author Draeius
 */
class Config {

    private $data = null;

    public function __construct($data) {
        $this->data = $data;
    }

    protected function getData(string $key, ?array $data = null) {
        if ($data == null) {
            $data = $this->data;
        }
        if (!array_key_exists($key, $data)) {
            throw new MissingConfigAttributeException('Konnte "' . $key . '" nicht finden.');
        }
        return $data[$key];
    }

    protected function getPrice($key, ?array $data = null): Price {
        if ($data == null) {
            $data = $this->getConfigData($key);
        } else {
            $data = $data[$key];
        }
        if (!array_key_exists('gold', $data)) {
            throw new MissingConfigAttributeException('Konnte "gold" in ' . $key . ' nicht finden.');
        }
        if (!array_key_exists('platin', $data)) {
            throw new MissingConfigAttributeException('Konnte "platin" in ' . $key . ' nicht finden.');
        }
        if (!array_key_exists('gems', $data)) {
            throw new MissingConfigAttributeException('Konnte "gems" in ' . $key . ' nicht finden.');
        }
        return new Price($data['gold'], $data['platin'], $data['gems']);
    }

    protected function getConfigData(string $key): array {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
        throw new MissingConfigException('Kann den Key "' . $key . '" in der Config nicht finden.');
    }

}
