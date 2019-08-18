<?php

namespace App\EventListener;

use App\Annotation\Security;
use App\Controller\BasicController;
use App\Util\RouteSecurityChecker;
use App\Util\Session\RhunSession;
use App\Util\TabIdentification\TabIdentifier;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
            if (!$this->checkAccess($event)) {
                $this->changeController($event);
            }
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
        $controller = explode('::', $event->getRequest()->attributes->get('_controller'));
        /* @var $annotation Security */
        $annotation = new Security();
        $annotation->merge($this->annotationReader->getClassAnnotation(new ReflectionClass($controller[0]), Security::class));
        /* @var $methodAnnotation Security */
        $annotation->merge($this->annotationReader->getMethodAnnotation(new ReflectionMethod($controller[0], $controller[1]), Security::class));

        if ($annotation) {
            $securityChecker = new RouteSecurityChecker($this->manager, $annotation);
            $success = $securityChecker->checkSecurity();
        }
        return $success;
    }

    private function changeController(FilterControllerEvent $event) {
        $event->setController(function () {
            return new RedirectResponse('/');
        });
    }

}
