<?php

namespace App\Entity\Items;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Description of Armor
 *
 * @author Draeius
 * @Entity
 * @Table(name="armors")
 */
class Armor extends Item{

    /**
     *
     * @var ArmorTemplate
     * @ManyToOne(targetEntity="ArmorTemplate")
     * @JoinColumn(name="armor_template_id", referencedColumnName="id")
     */
    protected $armorTemplate;

    function getArmorTemplate(): ArmorTemplate {
        return $this->armorTemplate;
    }

    function setArmorTemplate(ArmorTemplate $armorTemplate) {
        $this->armorTemplate = $armorTemplate;
    }

    function getDisplayTemplate() {
        return 'parts/armor.html.twig';
    }

}
