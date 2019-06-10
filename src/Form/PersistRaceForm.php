<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Form;

use App\Entity\Area;
use App\Repository\AreaRepository;
use App\Repository\RaceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;

/**
 * Description of PersistRaceForm
 *
 * @author Matthias
 */
class PersistRaceForm extends AbstractType {

    protected $id;
    protected $name;
    protected $coloredName;
    protected $city;
    protected $deathLocation;
    protected $location;
    protected $description;
    protected $allowedAreas;
    protected $allowed = true;

    /**
     *
     * @var AreaRepository
     */
    private $areaRepo;

    /**
     *
     * @var RaceRepo
     */
    private $raceRepo;

    function __construct(AreaRepository $areaRepo, RaceRepository $raceRepo) {
        $this->areaRepo = $areaRepo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('id', EntityType::class, [
                    'class' => Area::class,
                    'label' => 'Areal',
                    'placeholder' => '',
                    'query_builder' => function (AreaRepository $er) {
                        return $er->createQueryBuilder('r')
                                ->where('r.raceAllowed=true')
                                ->orderBy('r.name', 'ASC');
                    }
        ]);

//
//        $formModifier = function (FormInterface $form, Race $race = null) {
//            $areas = null === $race ? [] : $this->areaRepo->getAreasAllowingRaces();
//
//            $form->add('area', EntityType::class, [
//                'class' => 'App\Entity\Area',
//                'placeholder' => '',
//                'choices' => $areas,
//            ]);
//        };
//
//        $builder->addEventListener(
//                FormEvents::PRE_SET_DATA,
//                function (FormEvent $event) use ($formModifier) {
//            // this would be your entity, i.e. SportMeetup
//            $data = $event->getData();
//
//            $formModifier($event->getForm(), $data);
//        }
//        );
//
//        $builder->get('race')->addEventListener(
//                FormEvents::POST_SUBMIT,
//                function (FormEvent $event) use ($formModifier) {
//            // It's important here to fetch $event->getForm()->getData(), as
//            // $event->getData() will get you the client data (that is, the ID)
//            $race = $event->getForm()->getData();
//
//            // since we've added the listener to the child, we'll have to pass on
//            // the parent to the callback functions!
//            $formModifier($event->getForm()->getParent(), $race);
//        }
//        );
    }

    private function preSetData(FormEvent $event, &$formModifier) {
        // this would be your entity, i.e. SportMeetup
        $data = $event->getData();

        $formModifier($event->getForm(), $data);
    }

}
