<?php

namespace App\Form\EventSubscriber\PostSubmit;

use App\Entity\Area;
use App\Form\FormModifier\RaceByCityModifier;
use App\Repository\RaceRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Description of AddRaceFieldPSSubscriber
 *
 * @author Draeius
 */
class AddRaceFieldSubscriber implements EventSubscriberInterface {

    /**
     *
     * @var RaceRepository
     */
    private $raceRepo;

    function __construct(RaceRepository $raceRepo) {
        $this->raceRepo = $raceRepo;
    }

    public static function getSubscribedEvents() {
        return [FormEvents::POST_SUBMIT => 'postSubmit'];
    }

    public function postSubmit(FormEvent $event) {
        $form = $event->getForm()->getParent();
        
        $area = new Area();
        $area->setCity($event->getData());

        $modifier = new RaceByCityModifier($this->raceRepo);
        $modifier->modify($form, $area);
    }

}
