<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Form\Facade;

use App\Entity\Character;
use App\Form\DTO\CreateCharacterDTO;
use App\Repository\RaceRepository;
use App\Repository\UserRepository;
use App\Service\DateTimeService;
use App\Service\ItemService;
use App\Util\Session\RhunSession;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of CreateCharacterFacade
 *
 * @author Matthias
 */
class CreateCharacterFacade {

    /**
     *
     * @var UserRepository
     */
    private $userRepo;

    /**
     *
     * @var RaceRepository
     */
    private $raceRepo;

    /**
     *
     * @var EntityManagerInterface
     */
    private $eManager;

    function __construct(UserRepository $userRepo, RaceRepository $raceRepo, EntityManagerInterface $eManager) {
        $this->userRepo = $userRepo;
        $this->raceRepo = $raceRepo;
        $this->eManager = $eManager;
    }

    public function createUser(CreateCharacterDTO $dto): Character {
        $character = new Character();
        $session = new RhunSession();

        $character->setAccount($this->userRepo->find($session->getAccountID()));
        $character->setName($dto->name);
        $character->setGender($dto->gender);
        $character->setRace($this->raceRepo->find($dto->race));
        $character->setWeapon(ItemService::createWeapon($character->getRace()->getDefaultWeapon()));
        $character->setArmor(ItemService::createArmor($character->getRace()->getDefaultArmor()));
        $character->setLocation($character->getRace()->getLocation());
        $character->setLastActive(DateTimeService::getDateTime('NOW'));
        $character->setWallet(new Wallet());
        $character->setNewest(true);

        $this->eManager->createQueryBuilder()->update('App:Character', 'c')
                ->set('c.newest', 'false')->getQuery()->execute();
        $this->eManager->persist($character->getWeapon());
        $this->eManager->persist($character->getArmor());
        $this->eManager->persist($character);
        $this->eManager->flush();
        return $character;
    }

}
