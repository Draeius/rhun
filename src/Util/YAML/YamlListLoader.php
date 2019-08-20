<?php

namespace App\Util\YAML;

use Exception;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * Lädt eine YAML Datei.
 *
 * @author Draeius
 */
class YamlListLoader extends FileLoader {

    /**
     * 
     * @param type $resource
     * @param type $type
     * @return array
     */
    public function load($resource, $type = null) {
        try {
            $result = Yaml::parse(file_get_contents($resource));
        } catch (Exception $ex) {
            $result = [];
        }
        return $result;
    }

    public function supports($resource, $type = null): bool {
        return is_string($resource) && 'yml' === pathinfo(
                        $resource, PATHINFO_EXTENSION
        );
    }

}
