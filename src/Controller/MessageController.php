<?php

namespace App\Controller;

use App\Entity\Character;
use App\Entity\Message;
use App\Entity\User;
use App\Repository\CharacterRepository;
use App\Repository\UserRepository;
use App\Service\DateTimeService;
use App\Service\ParamGenerator\AccountMngmtParamGenerator;
use App\Util\Session\RhunSession;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of MessageController
 *
 * @author Draeius
 * @App\Annotation\Security(needAccount=true)
 */
class MessageController extends BasicController {

    /**
     * @Route("/mail/add/{uuid}", name="mail_add", defaults={"uuid" = null})
     * @param Request $request
     * @return type
     */
    public function addMessage(Request $request, $uuid, UserRepository $userRepo, CharacterRepository $charRepo) {
        $session = new RhunSession();
        /* @var $account User */
        $account = $userRepo->find($session->getAccountID());
        /* @var $author Character */
        $author = $charRepo->find($request->get('author'));

        if (!$author) {
            $session->error('Du musst erst einen Charakter auswählen.');
            return $this->forward($this->routeToControllerName('mail_show'));
        }

        if (!$account->ownsCharacter($author)) {
            $session->error('Der Charakter gehört dir nicht.');
            return $this->forward($this->routeToControllerName('mail_show'));
        }

        $addressee = $charRepo->findByName(trim($request->get('target')));

        if (!$addressee) {
            $session->error('Es gibt keinen Charakter mit dem Namen ' . $request->get('target'));
            return $this->forward($this->routeToControllerName('mail_show'));
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
        return $this->redirectToRoute('mail_show');
    }

    /**
     * @Route("/mail/delete/{uuid}", name="mail_delete", defaults={"uuid" = null})
     */
    public function deleteMessage(Request $request, $uuid) {
        $message = $this->getDoctrine()->getRepository('App:Message')->find($request->get('id'));
        $session = new RhunSession();

        if ($message->getAddressee()->getAccount()->getId() == $session->getAccountID()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($message);
            $entityManager->flush();
        }
        return $this->redirectToRoute('mail_show', ['uuid' => $uuid]);
    }

    /**
     * @Route("/mail/make_important/{uuid}", name="mail_important", defaults={"uuid" = null})
     */
    public function makeImportant(Request $request, $uuid) {
        $message = $this->getDoctrine()->getRepository('App:Message')->find($request->get('id'));
        if (!$message) {
            return;
        }

        $session = new RhunSession();
        if ($message->getAddressee()->getAccount()->getId() == $session->getAccountID()) {
            $message->setImportant(true);
            $this->getDoctrine()->getManager()->persist($message);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->redirectToRoute('mail_show', ['uuid' => $uuid]);
    }

    /**
     * @Route("/mail/make_unimportant/{uuid}", name="mail_unimportant", defaults={"uuid" = null})
     */
    public function makeUnimportant(Request $request, $uuid) {
        $message = $this->getDoctrine()->getRepository('AppBundle:Message')->find($request->get('id'));
        if (!$message) {
            return;
        }

        $session = new RhunSession();
        if ($message->getAddressee()->getAccount()->getId() == $session->getAccountID()) {
            $message->setImportant(false);
            $this->getDoctrine()->getManager()->persist($message);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->redirectToRoute('mail_show', ['uuid' => $uuid]);
    }

    /**
     * @Route("mail/get/{charId}", name="mail_get")
     */
    public function getMessagesForCharacter(Request $request, $charId, CharacterRepository $charRepo, UserRepository $userRepo) {
        $session = new RhunSession();
        $account = $userRepo->find($session->getAccountID());
        $character = $charRepo->findOneBy(['id' => $charId, 'account' => $account]);

        if (!$character) {
            return new JsonResponse(['error' => 'Du kannst diese Nachrichten nicht abrufen.']);
        }

        if ($charId == 0 || $charId == null) {
            $result = $this->getAllMessagesArray($account, $request->get('peek'));
        } else {
            $result = $this->createMessageBoxArray($character, $request->get('peek'));
        }

        return new JsonResponse($result);
    }

    /**
     * @Route("/mail/show/{uuid}", name="mail_show", defaults={"uuid" = null})
     */
    public function showMessageInterface(Request $request, $uuid, AccountMngmtParamGenerator $paramGenerator) {
        return $this->render($this->getSkinFile(), $paramGenerator->getMailParams($request->get('charId'), $request->get('charName')));
    }

    /**
     * 
     * @param type $routename
     * @return type
     * @Route("/mail/read/{messageId}/{uuid}", name="mail_read", defaults={"uuid" = null})
     */
    public function readMessage(string $messageId, string $uuid, CharacterRepository $charRepo, UserRepository $userRepo, EntityManagerInterface $eManager) {
        $session = new RhunSession();
        /* @var $account User */
        $account = $userRepo->find($session->getAccountID());
        $message = $eManager->getRepository('App:Message')->find($messageId);

        if (!$message && !$account->getCharacters()->contains($message->getAddressee())) {
            return new JsonResponse(['error' => 'Not your message.']);
        }

        $message->setRead(true);

        $eManager->persist($message);
        $eManager->flush();
        return new JsonResponse(['message' => 'OK']);
    }

    private function routeToControllerName($routename) {
        $routes = $this->get('router')->getRouteCollection();
        return $routes->get($routename)->getDefaults()['_controller'];
    }

    private function getAllMessagesArray(User $account, $peek) {
        $result = [];
        $allChars = $this->getDoctrine()->getRepository('App:Character')->findByAccount($account);
        foreach ($allChars as $character) {
            $result = array_merge($result, $this->createMessageBoxArray($character, $peek));
        }
        usort($result, function($a, $b) {
            $format = 'j.n.y G:i';
            $dateA = DateTime::createFromFormat($format, $a['createdAt']);
            $dateB = DateTime::createFromFormat($format, $b['createdAt']);
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
        $messages = $this->getDoctrine()->getRepository('App:Message')->findBy(['addressee' => $character], ['createdAt' => 'DESC']);
        $sentMessages = $this->getDoctrine()->getRepository('App:Message')->findBy(['sender' => $character], ['createdAt' => 'DESC']);

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
