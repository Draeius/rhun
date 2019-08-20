<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Twig;

use App\Entity\Partial\Interfaces\CharacterNameInterface;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig_SimpleFilter;

/**
 * Description of CharacterTwigExtension
 *
 * @author Draeius
 */
class CharacterTwigExtension extends AbstractExtension {

    /**
     *
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager) {
        $this->manager = $manager;
    }

    public function getFilters() {
        return array(
            new Twig_SimpleFilter('charName', array($this, 'getNameString'))
//            new Twig_SimpleFilter('isAdult', array($this, 'isAdult'))
        );
    }

    public function getNameString(CharacterNameInterface $character, $useAlternateName = true) {
        $serverSettings = $this->manager->getRepository('App:ServerSettings')->find(1);

        return $character->getFullName($useAlternateName && $serverSettings->getUseMaskedBios());
    }

    public function isAdult(Character $character) {
        $account = $character->getAccount();
        if (!$account->getBirthday()) {
            return '`GNein';
        }
        return time() > strtotime('+18 years', $account->getBirthday()->getTimestamp()) ? '`dJa' : '`GNein';
    }

}
