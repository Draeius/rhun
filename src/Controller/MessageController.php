<?php

namespace App\Controller;

use App\Entity\Character;
use App\Entity\Message;
use App\Entity\User;
use App\Service\DateTimeService;
use App\Service\NavbarService;
use App\Annotation\Security;
use App\Util\Session\RhunSession;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of MessageController
 *
 * @author Draeius
 * @Security(needAccount=true)
 */
class MessageController extends BasicController {

    /**
     * @Route("/mail/add/{uuid}", name="mail_add", defaults={"uuid" = "1"})
     * @param Request $request
     * @return type
     */
    public function addMessage(Request $request, $uuid) {
        $account = $this->getAccount();
        if ($uuid == '1') {
            $session = new RhunSession('');
        } else {
            $session = new RhunSession($uuid);
        }

        $rep = $this->getDoctrine()->getRepository('AppBundle:Character');
        $author = $rep->find($request->get('author'));

        if (!$author) {
            $session->error('Du musst erst einen Charakter auswählen.');
            return $this->forward($this->routeToControllerName('mail_show'), ['uuid' => $uuid]);
        }

        if (!$account->ownsCharacter($author)) {
            $session->error('Der Charakter gehört dir nicht.');
            return $this->forward($this->routeToControllerName('mail_show'), ['uuid' => $uuid]);
        }

        $addressee = $rep->findByName(trim($request->get('target')));

        if (!$addressee) {
            $session->error('Es gibt keinen Charakter mit dem Namen ' . $request->get('target'));
            return $this->forward($this->routeToControllerName('mail_show'), ['uuid' => $uuid]);
        }

        $subject = strip_tags(trim($request->get('subject')));
        if ($subject == '') {
            $subject = 'Kein Betreff';
        }

        $message = new Message();
        $message->setSender($author);
        $message->setAddressee($addressee);
        $message->setSubject($subject);
        $message->setContent(strip_tags(trim($request->get('content'))));
        $message->setCreated(DateTimeService::getDateTime('NOW'));
        $message->setRead(false);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($message);
        $manager->flush();
        return $this->redirectToRoute('mail_show', ['uuid' => $uuid]);
    }

    /**
     * @Route("/mail/delete/{uuid}", name="mail_delete", defaults={"uuid" = "1"})
     */
    public function deleteMessage(Request $request, $uuid) {
        $message = $this->getDoctrine()->getRepository('AppBundle:Message')->find($request->get('id'));

        if ($message->getAddressee()->getAccount()->getId() == $this->getAccount()->getId()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($message);
            $entityManager->flush();
        }
        return $this->redirectToRoute('mail_show', ['uuid' => $uuid]);
    }

    /**
     * @Route("/mail/make_important/{uuid}", name="mail_important", defaults={"uuid" = "1"})
     */
    public function makeImportant(Request $request, $uuid) {
        $message = $this->getDoctrine()->getRepository('AppBundle:Message')->find($request->get('id'));
        if (!$message) {
            return;
        }

        if ($message->getAddressee()->getAccount()->getId() == $this->getAccount()->getId()) {
            $message->setImportant(true);
            $this->getDoctrine()->getManager()->flush($message);
        }
        return $this->redirectToRoute('mail_show', ['uuid' => $uuid]);
    }

    /**
     * @Route("/mail/make_unimportant/{uuid}", name="mail_unimportant", defaults={"uuid" = "1"})
     */
    public function makeUnimportant(Request $request, $uuid) {

        $message = $this->getDoctrine()->getRepository('AppBundle:Message')->find($request->get('id'));
        if (!$message) {
            return;
        }

        if ($message->getAddressee()->getAccount()->getId() == $this->getAccount()->getId()) {
            $message->setImportant(false);
            $this->getDoctrine()->getManager()->flush($message);
        }
        return $this->redirectToRoute('mail_show', ['uuid' => $uuid]);
    }

    /**
     * @Route("mail/get/{charId}", name="mail_get")
     */
    public function getMessagesForCharacter(Request $request, $charId) {
        $account = $this->getAccount();
        $character = $this->getDoctrine()->getRepository('AppBundle:Character')->find($charId);

        if ($character && !$account->ownsCharacter($character)) {
            return new JsonResponse(['error' => 'Du kannst diese Nachrichten nicht abrufen.']);
        }

        if (!$character) {
            $result = $this->getAllMessagesArray($account, $request->get('peek'));
        } else {
            $result = $this->createMessageBoxArray($character, $request->get('peek'));
        }

        return new JsonResponse($result);
    }

    /**
     * @Route("/mail/show/{uuid}", name="mail_show", defaults={"uuid" = "1"})
     */
    public function showMessageInterface(Request $request, $uuid) {
        $builder = $this->get('app.navbar');
        /* @var $builder NavbarService */
        if ($uuid == '1' || !$uuid) {
            $builder->addNav('Zurück', 'charmanagement');
        } else {
            $builder->addNav('Zurück', 'world', ['uuid' => $uuid, 'locationId' => $this->getCharacter($uuid)->getLocation()->getId()]);
        }

        $vars = $this->getBaseVariables($builder, 'Taubenschlag');

        $vars['page'] = 'default/mail';
        $vars['chars'] = $this->getAccount()->getCharacters();
        $vars['messageRep'] = $this->getDoctrine()->getRepository('AppBundle:Message');
        $vars['selectedChar'] = $request->get('charId');
        $vars['targetName'] = $request->get('charName');
        $vars['uuid'] = $uuid == '0' ? '' : $uuid;

        return $this->render($this->getSkinFile(), $vars);
    }

    /**
     * 
     * @param type $routename
     * @return type
     * @Route("/mail/read/{messageId}/{uuid}", name="mail_read", defaults={"uuid" = "1"})
     */
    public function readMessage(string $messageId, string $uuid) {
        /* @var $account User */
        $account = $this->getAccount();
        $message = $this->getDoctrine()->getManager()->getRepository('AppBundle:Message')->find($messageId);

        if (!$message && !$account->getCharacters()->contains($message->getAddressee())) {
            return new JsonResponse(['error' => 'Not your message.']);
        }

        $message->setRead(true);

        $message = $this->getDoctrine()->getManager()->flush($message);
        return new JsonResponse(['message' => 'OK']);
    }

    private function routeToControllerName($routename) {
        $routes = $this->get('router')->getRouteCollection();
        return $routes->get($routename)->getDefaults()['_controller'];
    }

    private function getAllMessagesArray(User $account, $peek) {
        $result = [];
        $allChars = $account->getCharacters();
        foreach ($allChars as $character) {
            $result = array_merge($result, $this->createMessageBoxArray($character, $peek));
        }
        usort($result, function($a, $b) {
            $format = 'j.n.y G:i';
            $dateA = DateTime::createFromFormat($format, $a['created']);
            $dateB = DateTime::createFromFormat($format, $b['created']);
            if ($dateA < $dateB) {
                return 1;
            }
            if ($dateA > $dateB) {
                return -1;
            }
            return 0;
        });
        return $result;
    }

    private function createMessageBoxArray(Character $character, $peek) {
        $messages = $this->getDoctrine()->getRepository('AppBundle:Message')->findBy(['addressee' => $character], ['created' => 'DESC']);
        $sentMessages = $this->getDoctrine()->getRepository('AppBundle:Message')->findBy(['sender' => $character], ['created' => 'DESC']);

        $result = [];

        foreach ($messages as $message) {
            $result[] = $this->createMessageArray($message, false);
        }
        foreach ($sentMessages as $message) {
            $result[] = $this->createMessageArray($message, true);
        }
        return $result;
    }

    private function createMessageArray(Message $message, $sent) {
        return array(
            'id' => $message->getId(),
            'sender' => $message->getSender() ? $message->getSender()->getName() : 'System',
            'addressee' => $message->getAddressee()->getName(),
            'read' => $message->getRead(),
            'subject' => $message->getSubject(),
            'created' => $message->getCreated()->format('j.n.y G:i'),
            'content' => $this->render('parts/preview.html.twig', [
                'text' => $message->getContent()
            ])->getContent(),
            'important' => $message->getImportant(),
            'sent' => $sent
        );
    }

}
