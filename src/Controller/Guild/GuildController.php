<?php

namespace App\Controller\Guild;

use App\Controller\BasicController;
use App\Entity\Character;
use App\Entity\Guild;
use App\Entity\GuildHall;
use App\Entity\GuildInvitation;
use App\Entity\Location;
use App\Entity\Navigation;
use App\Repository\CharacterRepository;
use App\Repository\GuildInvitationRepository;
use App\Repository\GuildRepository;
use App\Service\ConfigService;
use App\Service\DateTimeService;
use App\Util\Session\RhunSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of GuildController
 *
 * @author Draeius
 * @Route("/guild")
 * @App\Annotation\Security(needCharacter=true, needAccount=true)
 */
class GuildController extends BasicController {

    /**
     * @Route("/create/{uuid}", name="guild_create")
     */
    public function createGuildAction(Request $request, $uuid, CharacterRepository $charRepo, GuildRepository $guildRepo, EntityManagerInterface $eManager,
            ConfigService $config) {
        $session = new RhunSession();
        $character = $charRepo->find($session->getCharacterID());

        $price = $config->getGuildConfig()->getGuildFoundingPrice();

        if ($character->getGuild()) {
            $session->error('Du bist bereits in einer Gilde.');
            return $this->redirectToWorld($character);
        }

        if (!$character->getWallet()->checkPrice($price)) {
            $session->error('Das ist wohl ein wenig zu teuer.');
            return $this->redirectToWorld($character);
        }

        if ($guildRepo->findByTag($request->get('tag'))) {
            $session->error('Dieser Tag ist bereits vergeben.');
            return $this->redirectToWorld($character);
        }
        if ($guildRepo->findByName($request->get('name'))) {
            $session->error('Dieser Name ist bereits vergeben.');
            return $this->redirectToWorld($character);
        }

        $this->addShortNews('`Mhat die Gilde [' . $request->get('tag') . '] ' . $request->get('name') . ' `Mgegründet.', $character);

        $this->createGuild($request->get('tag'), $request->get('name'), $character);

        $character->getWallet()->subtractPrice($price);
        $eManager->persist($character);
        $eManager->flush();

        return $this->redirectToWorld($character);
    }

    /**
     * @Route("/edit/{uuid}", name="guild_edit")
     */
    public function editGuild(Request $request, $uuid, CharacterRepository $charRepo, EntityManagerInterface $eManager) {
        $session = new RhunSession();
        $character = $charRepo->find($session->getCharacterID());

        /* @var $guild Guild */
        $guild = $character->getGuild();
        if (!$guild) {
            $session->error('Du bist in keiner Gilde.');
            return $this->redirectToWorld($character);
        }
        if ($guild->getMaster()->getId() != $character->getId()) {
            $session->error('Du bist kein Gildenmeister.');
            return $this->redirectToWorld($character);
        }

        $guild->setDescription($request->get('description'));
        $guild->setScript($request->get('script'));
        $guild->setAvatar($request->get('avatar'));

        $eManager->persist($guild);
        $eManager->flush();
        return $this->redirectToWorld($character);
    }

    /**
     * 
     * @param Request $request
     * @param string $uuid
     * 
     * @Route("/invite/{uuid}", name="guild_invite")
     */
    public function inviteCharacter(Request $request, string $uuid, CharacterRepository $charRepo, GuildRepository $guildRepo, EntityManagerInterface $eManager) {
        /* @var $guild Guild */
        $guild = $guildRepo->findOneBy(['master' => $this->getCharacter()]);
        $session = new RhunSession();
        $character = $charRepo->find($session->getCharacterID());

        if (!$guild) {
            $session->error('Du bist nicht der Meister der Gilde.');
            return $this->redirectToWorld($character);
        }

        /* @var $invTarget Character */
        $invTarget = $charRepo->findOneBy(['name' => $request->get('target')]);

        if (!$invTarget) {
            $session->error('Es gibt keinen Charakter mit diesem Namen.');
            return $this->redirectToWorld($character);
        }

        if ($eManager->getRepository('Guild:GuildInvitation')->findOneBy(['character' => $invTarget, 'guild' => $guild])) {
            $session->error('Du hast diesen Charakter bereits eingeladen.');
            return $this->redirectToWorld($character);
        }

        $invitation = new GuildInvitation();
        $invitation->setCharacter($invTarget);
        $invitation->setGuild($guild);
        $invitation->setCreated(DateTimeService::getDateTime('NOW'));

        $eManager->persist($invitation);
        $eManager->flush();

        $this->sendSystemPN('Gildeneinladung', 'Du wurdest in die Gilde ' . $guild->getName() . ' eingeladen.', $invTarget);

        $session->log('`gDu hast ' . $invTarget->getFullName() . ' `gerfolgreich eingeladen');

        return $this->redirectToWorld($character);
    }

    /**
     * 
     * @param int $invitationId
     * @param string $uuid
     * 
     * @Route("/accept/{invitationId}/{uuid}", name="guild_accept_inv")
     */
    public function acceptInvitation($invitationId, string $uuid, CharacterRepository $charRepo, GuildInvitationRepository $invRepo) {
        $invitation = $invRepo->find($invitationId);
        $session = new RhunSession();
        $character = $charRepo->find($session->getCharacterID());
        if (!$invitation) {
            $session->log('Diese Einladung gibt es nicht mehr.');
            return $this->redirectToWorld($character);
        }

        if ($character->getId() != $invitation->getCharacter()->getId()) {
            $session->log('Dies ist nicht deine Einladung.');
            return $this->redirectToWorld($character);
        }
        if ($character->getGuild()) {
            $session->log('Du bist bereits in einer Gilde.');
            return $this->redirectToWorld($character);
        }

        $guild = $invitation->getGuild();
        $guild->addMember($character);

        $this->addShortNews('`qist jetzt Mitglied in der Gilde [' . $guild->getTag() . '] ' . $guild->getName() . '`q.');

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($character);
        $manager->persist($guild);
        $manager->remove($invitation);
        $manager->flush();

        $session->log('`gDu bist erfolgreich der Gilde ' . $guild->getName() . ' `gbeigetreten.');

        return $this->redirectToWorld($character);
    }

    /**
     * 
     * @param int $invitationId
     * @param string $uuid
     * 
     * @Route("/refuse/{invitationId}/{uuid}", name="guild_refuse_inv")
     */
    public function refuseInvitation($invitationId, string $uuid, CharacterRepository $charRepo, GuildInvitationRepository $invRepo) {
        $invitation = $invRepo->find($invitationId);
        $session = new RhunSession();
        $character = $charRepo->find($session->getCharacterID());
        if (!$invitation) {
            $session->log('Diese Einladung gibt es nicht mehr.');
            return $this->redirectToWorld($character);
        }

        if ($character->getId() != $invitation->getCharacter()->getId()) {
            $session->log('Dies ist nicht deine Einladung.');
            return $this->redirectToWorld($character);
        }

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($invitation);
        $manager->flush();

        return $this->redirectToWorld();
    }

    /**
     * 
     * @param Request $request
     * @param string $uuid
     * 
     * @Route("/kick/{charId}/{uuid}", name="guild_kick_member")
     */
    public function kickCharacter($charId, string $uuid, CharacterRepository $charRepo, EntityManagerInterface $eManager) {
        /* @var $guild Guild */
        $guild = $this->getDoctrine()->getRepository('GuildBundle:Guild')->findOneBy(['master' => $this->getCharacter()]);
        $session = new RhunSession();
        $character = $charRepo->find($session->getCharacterID());
        if (!$guild) {
            $session->error('Du bist nicht der Meister der Gilde.');
            return $this->redirectToWorld($character);
        }

        $toKick = $charRepo->find($charId);

        if ($toKick && $guild->hasMember($toKick)) {
            $toKick->setGuild(null);
            $toKick->setLocation($toKick->getRace()->getStartingLocation());
            $eManager->persist($toKick);
            $eManager->flush();
        }
        return $this->redirectToWorld($character);
    }

    /**
     * 
     * @param string $uuid
     * 
     * @Route("/leave/{uuid}", name="guild_leave")
     */
    public function leaveGuild(string $uuid, CharacterRepository $charRepo, EntityManagerInterface $eManager) {
        $session = new RhunSession();
        $character = $charRepo->find($session->getCharacterID());

        if ($character->getGuild()->getMaster()->getId() == $character->getId()) {
            $session->error('Du bist der Meister dieser Gilde und kannst sie nicht verlassen.');
            return $this->redirectToWorld($character);
        }

        $character->setGuild(null);
        $character->setLocation($character->getRace()->getStartingLocation());
        $eManager->flush($character);

        return $this->redirectToWorld($character);
    }

    /**
     * 
     * @param Request $request
     * @param string $uuid
     * 
     * @Route("/change/master/{charId}/{uuid}", name="guild_change_master")
     */
    public function changeMaster($charId, string $uuid, CharacterRepository $charRepo, GuildRepository $guildRepo, EntityManagerInterface $eManager) {
        $session = new RhunSession();
        $character = $charRepo->find($session->getCharacterID());
        /* @var $guild Guild */
        $guild = $guildRepo->findOneBy(['master' => $character]);

        if (!$guild) {
            $session->error('Du bist nicht der Meister der Gilde.');
            return $this->redirectToWorld($character);
        }

        $newMaster = $charRepo->find($charId);

        if ($newMaster && $guild->hasMember($newMaster)) {
            $guild->setMaster($newMaster);
            $eManager->persist($guild);
            $eManager->flush();

            $this->addShortNews('`qist der neue Meister der Gilde [' . $guild->getTag() . '] ' . $guild->getName() . '`q.', $newMaster);
        }

        return $this->redirectToWorld($character);
    }

    /**
     * 
     * @param string $uuid
     * 
     * @Route("/delete/{uuid}", name="guild_delete")
     */
    public function deleteGuild(string $uuid, CharacterRepository $charRepo, GuildRepository $guildRepo, GuildInvitationRepository $invRepo, EntityManagerInterface $eManager) {
        $session = new RhunSession();
        $character = $charRepo->find($session->getCharacterID());
        /* @var $guild Guild */
        $guild = $guildRepo->findOneBy(['master' => $character]);
        if ($guild->getMaster()->getId() != $character->getId()) {
            return $this->redirectToWorld($character);
        }

        $locations = $guild->getGuildHall()->getLocations();
        foreach ($locations as $loc) {
            $presentChars = $charRepo->findByLocation($loc);
            foreach ($presentChars as $char) {
                $char->setLocation($char->getRace()->getStartingLocation());
                $eManager->persist($char);
            }
        }

        $invitations = $invRepo->findByGuild($guild);
        foreach ($invitations as $inv) {
            $eManager->remove($inv);
        }

        $allMembers = $guild->getMembers();
        foreach ($allMembers as $member) {
            $member->setGuild(null);
            $eManager->persist($member);
        }

        $this->addShortNews('`Lhat die Gilde [' . $guild->getTag() . '] ' . $guild->getName() . ' `Laufgelöst.');
        $eManager->remove($guild);
        $eManager->flush();

        return $this->redirectToWorld($character);
    }

    /**
     * @Route("/show/{id}/{uuid}", name="guild_show")
     */
    public function showGuildDescription($id, $uuid) {
        $guild = $this->getDoctrine()->getManager()->getRepository('GuildBundle:Guild')->find($id);
        if (!$guild) {
            $session = new RhunSession($uuid);
            $session->error('Diese Gilde gibt es scheinbar nicht.');
            return $this->redirectToWorld($uuid);
        }
        $builder = $this->get('app.navbar');
        if ($uuid != '1') {
            $builder->addNav('Zurück zur Kämpferliste', 'list', ['uuid' => $uuid]);
            $builder->addNav('Zurück', 'world', ['uuid' => $uuid, 'locationId' => $this->getCharacter($uuid)->getLocation()->getId()]);
        } else {
            $builder->addNav('Zurück zur Kämpferliste', 'list');
            $builder->addNav('Zurück zur Charakterübersicht', 'charmanagement');
        }

        $base = $this->getBaseVariables($builder, 'Gildenbeschreibung');
        $vars = array_merge($base, [
            'page' => 'default/guildDescription',
            'guild' => $guild
        ]);

        return $this->render($this->getSkinFile(), $vars);
    }

    /**
     * 
     * @param Request $request
     * @param string $uuid
     * 
     * @Route("/change/training/{uuid}", name="guild_change_training")
     */
    public function changeGuildTraining(Request $request, string $uuid, CharacterRepository $charRepo, GuildRepository $guildRepo, EntityManagerInterface $eManager,
            ConfigService $config) {
        $session = new RhunSession();
        /* @var $character Character */
        $character = $charRepo->find($session->getCharacterID());
        /* @var $guild Guild */
        $guild = $guildRepo->findOneBy(['master' => $character]);

        if (!$guild) {
            $session->error('Du bist nicht der Meister der Gilde.');
            return $this->redirectToWorld($character);
        }

        $price = $config->getGuildConfig()->getChangeTrainingPrice();

        if ($character->getWallet()->checkPrice($price)) {
            $session->error('Das kannst du dir nicht leisten.');
            return $this->redirectToWorld($character);
        }

        $character->getWallet()->subtractPrice($price->multiply(-1));
        $guild->setBuffedAttribute($request->get('attribute'));

        $eManager->persist($guild);
        $eManager->persist($character);
        $eManager->flush();

        return $this->redirectToWorld($character);
    }

    private function createGuild(string $tag, string $name, Character $character) {
        $guild = new Guild();
        $guild->setMaster($character);
        $guild->addMember($character);
        $guild->setTag($tag);
        $guild->setName($name);

        $guild->setGuildHall($this->createGuildLocations($character->getLocation()));

        $manager = $this->getDoctrine()->getManager();

        $manager->persist($guild);
        $manager->persist($character);
        $manager->flush();
    }

    private function createGuildLocations(Location $guildLocation) {
        $guildHall = new GuildHall();
        $area = $this->getDoctrine()->getRepository('App:Area')->find(17);

        $entrance = new Location();
        $entrance->setAdult(false);
        $entrance->setArea($area);
        $entrance->setDescriptionSpring('Die Eingangshalle dieser Gilde.');
        $entrance->setColoredName('Eingangshalle');
        $entrance->setName('Eingangshalle');
        $entrance->setPost(true);


        $guildHall->setEntrance($entrance);

        $administration = new Location();
        $administration->setAdult(false);
        $administration->setArea($area);
        $administration->setDescriptionSpring('Die Administration dieser Gilde.');
        $administration->setName('Administration');
        $administration->setColoredName('Administration');
        $administration->setGuildAdmin(true);


        $manager = $this->getDoctrine()->getManager();
        $manager->persist($entrance);
        $manager->persist($administration);

        $this->addNavs($entrance, $administration, $guildLocation);

        $guildHall->setLocations([$entrance, $administration]);

        $manager->persist($guildHall);
        return $guildHall;
    }

    private function addNavs(Location &$entrance, Location &$administration, Location $guildLocation) {
        $entr2adm = new Navigation();
        $entr2adm->setColoredName('Zur Administration');
        $entr2adm->setLocation($entrance);
        $entr2adm->setTargetLocation($administration);

        $entrLeave = new Navigation();
        $entrLeave->setColoredName('Gildenhalle verlassen');
        $entrLeave->setLocation($entrance);
        $entrLeave->setTargetLocation($guildLocation);
        $entrLeave->setNavbarIndex(-1);

        $adm2entr = new Navigation();
        $adm2entr->setColoredName('Zurück');
        $adm2entr->setLocation($administration);
        $adm2entr->setTargetLocation($entrance);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($entr2adm);
        $manager->persist($entrLeave);
        $manager->persist($adm2entr);
    }

}
