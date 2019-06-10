<?php

namespace App\EventListener;

use App\Controller\BasicController;
use App\Util\Session\RhunSession;
use App\Util\TabIdentification\TabIdentifier;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * Description of ControllerListener
 *
 * @author Draeius
 */
class ControllerListener {

    /**
     *
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     *
     * @var Reader
     */
    private $annotationReader;

    /**
     *
     * @var ControllerResolverInterface
     */
    private $resolver;

    /**
     *
     * @var Stopwatch
     */
    private $stopwatch;

    public function __construct(EntityManagerInterface $manager, Reader $reader, ControllerResolverInterface $resolver, Stopwatch $stopwatch) {
        $this->manager = $manager;
        $this->annotationReader = $reader;
        $this->resolver = $resolver;
        $this->stopwatch = $stopwatch;
    }

    public function onKernelController(FilterControllerEvent $event) {
        $this->stopwatch->start('onKernelController');
//        $simulator = CronFactory::FACTORY($this->manager);
//        $simulator->executeJobs();
//        $this->manager->persist($simulator->getLastExecutionTimes());

        if ($event->getController()[0] instanceof BasicController) {
            $this->initializeTabIdentifier($event);
//            $this->updateLastActive($event->getController()[0]);
//            if (!$this->checkAccess($event)) {
//                $this->changeController($event);
//            }
        }
//        $this->manager->flush();
        $this->stopwatch->stop('onKernelController');
    }

    private function initializeTabIdentifier(FilterControllerEvent &$event) {
        if ($event->getRequest()->attributes->has('uuid')) {
            $uuid = $event->getRequest()->attributes->get('uuid');
        } else {
            $uuid = null;
        }
        $tabIdentifier = new TabIdentifier($uuid);
        $event->getController()[0]->setTabIdentifier($tabIdentifier);
        RhunSession::SET_TAB_IDENTIFIER($tabIdentifier);
    }

    private function updateLastActive(BasicController $controller) {
//        if (!$controller->getUuid() || $controller->getUuid() === '1') {
//            return;
//        }
//        /* @var $character Character */
//        $character = $controller->getCharacter($controller->getUuid());
//        if ($character) {
//            $character->setLastActive(DateTimeService::getDateTime('NOW'));
//            $this->manager->persist($character);
//        }
    }

    private function checkAccess(FilterControllerEvent &$event) {
//        $controller = explode('::', $event->getRequest()->attributes->get('_controller'));
//        /* @var $annotation Security */
//        $annotation = $this->annotationReader->getMethodAnnotation(new ReflectionMethod($controller[0], $controller[1]), 'App\Security\Annotation\Security');
//
//        if (!$annotation) {
//            return true;
//        }
//        $securityChecker = new RouteSecurityChecker($event->getController()[0], $annotation->getNeedAccount(), $annotation->getNeedCharacter(), $annotation->getUserRole());
//
//        return $securityChecker->checkSecurity();
    }

    private function changeController(FilterControllerEvent $event) {
//        $fakeRequest = $event->getRequest()->duplicate(null, null, array('_controller' => 'App\Controller\PreLoginController::indexAction'));
//        $controller = $this->resolver->getController($fakeRequest);
//        $event->setController($controller);
    }

}
