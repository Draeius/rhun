<?php

namespace App\Form\FormModifier;

use App\Entity\Area;
use App\Entity\Race;
use App\Repository\RaceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormInterface;

/**
 * Description of RaceByCityModifier
 *
 * @author Matthias
 */
class RaceByCityModifier {

    /**
     *
     * @var RaceRepository
     */
    private $raceRepo;

    function __construct(RaceRepository $raceRepo) {
        $this->raceRepo = $raceRepo;
    }

    public function modify(FormInterface $form, Area $city): void {
        $form->add('race', EntityType::class, [
            'class' => Race::class,
            'label' => 'Startort',
            'placeholder' => 'Erst Areale auswählen',
            'choice_label' => 'name',
            'choices' => $this->raceRepo->getRacesInArea($city)
        ]);
    }

}
