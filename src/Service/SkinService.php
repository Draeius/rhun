<?php

namespace App\Service;

use App\Util\Skin;
use App\Util\YAML\YamlListLoader;
use App\Util\YAML\YamlSkinLoader;
use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Description of SkinService
 *
 * @author Draeius
 */
class SkinService {

    const DEFAULT_SKIN = 'fire';

    /**
     *
     * @var string
     */
    private $rootDir;

    public function __construct(string $rootDir) {
        $this->rootDir = $rootDir;
    }

    public function getSkinByName(string $name): Skin {
        if ($name == null || $name == '') {
            return $this->getDefaultSkin();
        }
        return $this->getSkin($name);
    }

    public function getDefaultSkin(): Skin {
        return $this->getSkin(self::DEFAULT_SKIN);
    }

    public function getSkinList() {
        $configDirectories = array($this->rootDir);

        $fileLocator = new FileLocator($configDirectories);
        try {
            $yamlSkinFile = $fileLocator->locate('skinlist.yml');
        } catch (FileLocatorFileNotFoundException $ex) {
            VarDumper::dump('skinlist.yml not found. Please make sure it exists in ' . $this->rootDir);
            return [];
        }

        $loaderResolver = new LoaderResolver(array(new YamlListLoader($fileLocator)));
        $delegatingLoader = new DelegatingLoader($loaderResolver);

        return $delegatingLoader->load($yamlSkinFile);
    }

    private function getSkin(string $name) {
        $yaml = $this->getSkinYaml($name);
        return new Skin($name, $yaml);
    }

    private function getSkinYaml(string $name) {
        $configDirectories = array($this->rootDir);

        $fileLocator = new FileLocator($configDirectories);
        try {
            $yamlSkinFile = $fileLocator->locate($name . '.yml');
        } catch (FileLocatorFileNotFoundException $ex) {
            VarDumper::dump('SkinConfig ' . $name . '.yml not found. Using default');
            $yamlSkinFile = $fileLocator->locate(self::DEFAULT_SKIN . '.yml');
        }

        $loaderResolver = new LoaderResolver(array(new YamlSkinLoader($fileLocator)));
        $delegatingLoader = new DelegatingLoader($loaderResolver);

        return $delegatingLoader->load($yamlSkinFile);
    }

}
