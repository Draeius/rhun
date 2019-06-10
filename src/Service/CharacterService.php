<?php

namespace App\Service;

use App\Entity\User;

/**
 * Description of CharacterService
 *
 * @author Draeius
 */
class CharacterService {

    public static function getNeededPosts(User $account) {
        $needed = floor((pow($account->getMaxChars(), 2) + $account->getMaxChars()) / 2);
        if ($needed < 15) {
            return 15;
        }
        return $needed;
    }

    public static function getNeededGems(User $account) {
        return 1000;
    }

//    
//    public function createChar($name, $gender, User $acct, Race $race, Weapon $weapon, Armor $armor): Character {
//        $char = new Character();
//        $char->setName($name);
//        $char->setAccount($acct);
//        $char->setGender($gender);
//        $char->setRace($race);
//        $char->setLocation($race->getStartingLocation());
//        $char->setWeapon($weapon);
//        $char->setArmor($armor);
//        $char->setWallet(new Wallet());
//        $char->setAttributes(new CharacterAttributes());
//
//        return $char;
//    }
//
//    /**
//     * 
//     * @param EntityManager $manager
//     * @param Character $character
//     * @param Item $item
//     * @param type $amount
//     * @return type
//     * @deprecated since version number
//     */
//    public static function addItemToInventory(EntityManager $manager, Character &$character, Item $item, $amount) {
//        //loop all items
//        foreach ($character->getInventory() as $inventoryItem) {
//            //check if item has same id
//            if ($inventoryItem->getItem()->getId() == $item->getId()) {
//                //increment item amount
//                $inventoryItem->setAmount($inventoryItem->getAmount() + $amount);
//                //persist
//                $manager->flush($inventoryItem);
//                return;
//            }
//        }
//        //create new item
//        $toAdd = new InventoryItem();
//        $toAdd->setAmount($amount);
//        $toAdd->setItem($item);
//        $toAdd->setOwner($character);
//        //add item
//        $manager->persist($toAdd);
//        $manager->flush();
//    }
//
//    public static function getNameString(Character $character, $useMasked = false) {
//        if ($useMasked) {
//            foreach ($character->getBiography() as $bio) {
//                if ($bio->getMaskedBall()) {
//                    return $bio->getAlternateCharName();
//                }
//            }
//            return $character->getGender() ? 'Maskierter Fremder' : 'Maskierte Fremde';
//        }
//        $title = $character->getTitle();
//        $coloredName = $character->getColoredName();
//        if (!$title) {
//            return '<span>' . ($character->getGender() ? 'Fremder' : 'Fremde') . '</span> <span>' . ($coloredName ? $coloredName->getName() : $character->getName()) . '</span>';
//        }
//        if (!$title->getIsInFront()) {
//            return '<span>' . ($coloredName ? $coloredName->getName() : $character->getName()) . '</span> <span>' . $title->getTitle() . '</span>';
//        }
//        return '<span>' . $title->getTitle() . '</span> <span>' . ($coloredName ? $coloredName->getName() : $character->getName()) . '</span>';
//    }
//
//    public static function getBioHref(Character $character) {
//        if ($character) {
//            return 'main/biography.php?character=' . $character->getId();
//        } else {
//            return 'biography.php?character=' . $character->getId();
//        }
//    }
//
//    public static function handleDeath(EntityManager $manager, Character &$character, $looseWallet = true, $relocate = false, $expLoss = 0.1) {
//        if ($looseWallet) {
//            $character->getWallet()->setGold(0);
//            $character->getWallet()->setPlatin(0);
//        }
//        if ($relocate) {
//            $character->setLocation($character->getRace()->getDeathLocation());
//        }
//        if ($expLoss > 1) {
//            $expLoss = 1;
//        } else if ($expLoss < 0) {
//            $expLoss = 0;
//        }
//        $character->setExp(round($character->getExp() * (1 - $expLoss)));
//
//        $character->setCurrentHP(0);
//        $character->setDead(true);
//        $manager->persist($character);
//        $manager->persist($character->getWallet());
//        $manager->flush($character);
//    }
//
//    public static function deleteCharacter(EntityManager $em, Character $character) {
//        self::deleteCharForeignKeyEntities($em, $character);
//        $em->remove($character);
//        $em->flush();
//    }
//
//    private static function deleteCharForeignKeyEntities(EntityManager $em, Character $character) {
//        $em->remove($character->getWallet());
//        $em->remove($character->getAttributes());
//
//        self::deleteEntities($em, $em->getRepository('App:ShortNews')->findByCharacter($character));
//        self::deleteEntities($em, $em->getRepository('App:Biography')->findByOwner($character));
//        self::deleteEntities($em, $em->getRepository('App:DiaryEntry')->findByOwner($character));
//        self::deleteEntities($em, $em->getRepository('App:ColoredName')->findByOwner($character));
//        self::deleteEntities($em, $em->getRepository('App:Title')->findByOwner($character));
//        $inventory = $em->getRepository('App:InventoryItem')->findByOwner($character);
//        foreach ($inventory as $invItem) {
//            if ($invItem->getItem() instanceof Weapon || $invItem->getItem() instanceof Armor) {
//                $item = $invItem->getItem();
//                $em->remove($invItem);
//                $em->remove($item);
//            }
//            $em->remove($invItem);
//        }
//        self::deleteEntities($em, $em->getRepository('App:Post')->findByAuthor($character));
//        self::deleteEntities($em, $em->getRepository('App:Message')->findBySender($character));
//        self::deleteEntities($em, $em->getRepository('App:Message')->findByAddressee($character));
//        $characterHouses = $em->getRepository('HouseBundle:House')->findByOwner($character);
//        foreach ($characterHouses as $house) {
//            $rooms = $house->getRooms();
//            foreach ($rooms as $room) {
//                $chars = $em->getRepository('App:Character')->findByLocation($room->getLocation());
//                if ($chars) {
//                    foreach ($chars as $char) {
//                        $char->setLocation($char->getRace()->getStartingLocation());
//                    }
//                }
//            }
//        }
//        self::deleteEntities($em, $characterHouses);
//        $houses = $em->getRepository('HouseBundle:House')->findAll();
//        foreach ($houses as $house) {
//            if ($house->isInhabitant($character)) {
//                $house->removeInhabitant($character);
//                $em->persist($house);
//            }
//        }
//    }
//
//    private static function deleteEntities(EntityManager $em, $entities) {
//        foreach ($entities as $item) {
//            $em->remove($item);
//        }
//    }
}
