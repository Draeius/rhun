<?php

namespace App\Util;

/**
 *
 * @author Draeius
 */
class Skin {

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var string[]
     */
    private $params;

    public function __construct($name, $params) {
        $this->name = $name;
        $this->params = $params;
    }

    public function getName() {
        return $this->name;
    }

    public function getParams() {
        return $this->params;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setParams($params) {
        $this->params = $params;
    }

    public function getSkinFile() {
        return 'skins/' . $this->params['skin_base_dir'] . '/' . $this->params['skin_file'];
    }
    
    public function __toString() {
        return $this->getSkinFile();
    }

}
