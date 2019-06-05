<?php

namespace App\Util\YAML;

use Exception;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * 
 *
 * @author Draeius
 */
class YamlSkinLoader extends FileLoader {

    /**
     * 
     * @param type $resource
     * @param type $type
     * @return array
     */
    public function load($resource, $type = null) {
        $default = null;
        if (substr($resource, -1 * strlen(SkinService::DEFAULT_SKIN . 'yml') - 1) != SkinService::DEFAULT_SKIN . '.yml') {
            $default = $this->import($this->getLocator()->locate(SkinService::DEFAULT_SKIN . '.yml'), $type);
        }

        if ($default) {
            return array_merge($default, $this->parseYaml($resource));
        }
        return $this->parseYaml($resource);
    }

    private function parseYaml($resource) {
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
