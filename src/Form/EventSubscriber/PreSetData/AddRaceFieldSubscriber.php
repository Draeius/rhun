<?php

namespace App\Form\EventSubscriber\PreSetData;

use App\Entity\Area;
use App\Form\FormModifier\RaceByCityModifier;
use App\Repository\RaceRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Description of AddRaceFieldPSDSubscriber
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
        return [FormEvents::PRE_SET_DATA => 'preSetData'];
    }

    public function preSetData(FormEvent $event) {
        $form = $event->getForm();
        $area = new Area();
        $area->setCity('none');

        $modifier = new RaceByCityModifier($this->raceRepo);
        $modifier->modify($form, $area);
    }

}
