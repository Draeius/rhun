<?php

namespace App\Service;

use App\Util\Price;

/**
 *
 * @author Draeius
 */
class Reward {

    /**
     *
     * @var Price
     */
    private $price;
    private $increasePostCounter = 0;

    public function __construct() {
        $this->price = new Price(0, 0, 0);
    }

    function getPrice(): Price {
        return $this->price;
    }

    function getIncreasePostCounter() {
        return $this->increasePostCounter;
    }

    function setPrice(Price $price) {
        $this->price = $price;
    }

    function setIncreasePostCounter($increasePostCounter) {
        $this->increasePostCounter = $increasePostCounter;
    }

}
