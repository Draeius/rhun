<?php

namespace App\Service;

use App\Util\Config\Config;
use App\Util\Config\GuildConfig;
use App\Util\Config\HouseConfig;
use App\Util\Config\RolePlayConfig;
use App\Util\Config\Settings;
use App\Util\YAML\YamlListLoader;
use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\VarDumper\VarDumper;

/**
 * Description of ConfigService
 *
 * @author Matthias
 */
class ConfigService {

    /**
     *
     * @var string
     */
    private $rootDir;

    /**
     *
     * @var Config
     */
    private static $configData = null;

    public function __construct(string $rootDir) {
        $this->rootDir = $rootDir;
    }

    public function getRpConfig(): RolePlayConfig {
        return new RolePlayConfig($this->getConfig()['role_play']);
    }

    public function getHouseConfig(): HouseConfig {
        return new HouseConfig($this->getConfig()['house']);
    }

    public function getGuildConfig(): GuildConfig {
        return new GuildConfig($this->getConfig()['guild']);
    }

    public function getSettings(): Settings {
        return new Settings($this->getConfig()['settings']);
    }

    private function getConfig(): array {
        if (!self::$configData) {
            self::$configData = $this->getConfigYaml();
        }
        return self::$configData;
    }

    private function getConfigYaml() {
        $configDirectories = array($this->rootDir . '/config');

        $fileLocator = new FileLocator($configDirectories);
        try {
            $yamlSkinFile = $fileLocator->locate('game_config.yml');
        } catch (FileLocatorFileNotFoundException $ex) {
            VarDumper::dump('game_config.yml not found. Please ensure that the file is present.');
        }

        $loaderResolver = new LoaderResolver(array(new YamlListLoader($fileLocator)));
        $delegatingLoader = new DelegatingLoader($loaderResolver);

        return $delegatingLoader->load($yamlSkinFile);
    }

}
