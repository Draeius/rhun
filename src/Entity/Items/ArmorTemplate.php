<?php

namespace App\Entity\Items;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

/**
 * Armor that the player can use
 *
 * @author Draeius
 * @Entity(repositoryClass="App\Repository\ArmorTemplateRepository")
 * @Table(name="templates_armor")
 */
class ArmorTemplate extends Item {

    /**
     *
     * @var int The armor's defense
     * @Column(type="integer", nullable=false)
     */
    protected $armorType;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $attribute;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $minAttribute;

    /**
     *
     * @var int
     * @Column(type="integer")
     */
    protected $defense;

    /**
     * Die Resistenzen dieser Rüstung
     *
     * @var Collection|array
     * @ManyToMany(targetEntity="DamageType")
     * @JoinTable(name="armor_resistances",
     *      joinColumns={@JoinColumn(name="armor_templ_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="damage_type_id", referencedColumnName="id")}
     *      )
     */
    protected $resistances;

    /**
     * Die Verwundbarkeiten dieser Rüstung
     *
     * @var Collection|array
     * @ManyToMany(targetEntity="DamageType")
     * @JoinTable(name="armor_vulnerabilities",
     *      joinColumns={@JoinColumn(name="armor_templ_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="damage_type_id", referencedColumnName="id")}
     *      )
     */
    protected $vulnerabilities;

    public function getArmorType() {
        return $this->armorType;
    }

    public function getAttribute() {
        return $this->attribute;
    }

    public function getMinAttribute() {
        return $this->minAttribute;
    }

    function getDefense() {
        return $this->defense;
    }

    function getResistances() {
        return $this->resistances;
    }

    function getVulnerabilities() {
        return $this->vulnerabilities;
    }

    public function setArmorType($armorType) {
        $this->armorType = $armorType;
    }

    public function setAttribute($attribute) {
        $this->attribute = $attribute;
    }

    public function setMinAttribute($minAttribute) {
        $this->minAttribute = $minAttribute;
    }

    function setDefense($defense) {
        $this->defense = $defense;
    }

    function setResistances($resistances) {
        $this->resistances = $resistances;
    }

    function setVulnerabilities($vulnerabilities) {
        $this->vulnerabilities = $vulnerabilities;
    }

    function getDisplayTemplate() {
        return 'parts/armor.html.twig';
    }

}
