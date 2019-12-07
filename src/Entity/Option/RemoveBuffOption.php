<?php

namespace App\Entity\Option;

use App\Entity\BuffTemplate;
use App\Entity\Character;
use App\Util\Price;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * 
 * @Entity
 * @Table(name="options_rem_buff")
 */
class RemoveBuffOption extends Option {

    /**
     *
     * @var BuffTemplate
     * @ManyToOne(targetEntity="App\Entity\BuffTemplate")
     * @JoinColumn(name="buff_templ_id", referencedColumnName="id")
     */
    protected $buffTemplate;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $priceGold;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $pricePlatin;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $priceGems;

    /**
     *
     * @var bool
     * @Column(type="boolean")
     */
    protected $priceRequired;

    public function execute(EntityManagerInterface $eManager, Character $character) {
        $buff = $character->findBuffByTemplate($this->buffTemplate);
        $price = new Price($this->priceGold, $this->pricePlatin, $this->priceGems);
        if ($buff && (!$this->priceRequired || $character->getWallet()->checkPrice($price))) {
            $character->removeBuff($buff);
            $character->getWallet()->addPrice($this->price->multiply(-1));

            $eManager->remove($buff);
            $eManager->persist($character);
            $eManager->flush();
        }
    }

}
