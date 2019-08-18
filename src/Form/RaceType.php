<?php

namespace App\Form;

use App\Entity\Area;
use App\Entity\Location;
use App\Entity\Race;
use App\Repository\AreaRepository;
use App\Repository\LocationRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RaceType extends AbstractType {

    /**
     *
     * @var AreaRepository
     */
    private $areaRepo;

    /**
     *
     * @var LocationRepository
     */
    private $locationRepo;

    function __construct(AreaRepository $areaRepo, LocationRepository $locationRepo) {
        $this->areaRepo = $areaRepo;
        $this->locationRepo = $locationRepo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', TextType::class, ['label' => 'Name'])
                ->add('coloredName', TextType::class, ['label' => 'farbiger Name'])
                ->add('allowed', CheckboxType::class, ['label' => 'Kann ausgewählt werden'])
                ->add('description', TextareaType::class, ['label' => 'Beschreibung'])
                ->add('city', EntityType::class, [
                    'class' => Area::class,
                    'label' => 'Heimatstadt',
                    'placeholder' => 'Bitte auswählen',
                    'choice_label' => 'name',
                    'choice_value' => 'city',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('a')
                                ->andWhere('a.raceAllowed=true')
                                ->orderBy('a.name', 'ASC');
                    }
                ])
                ->add('allowedAreas', EntityType::class, [
                    'class' => Area::class,
                    'label' => 'Darf betreten',
                    'multiple' => true,
                    'choice_label' => 'name',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('a')
                                ->orderBy('a.name', 'ASC');
                    }
        ]);

        $formModifier = function (FormInterface $form, $allowedAreas = null) {
            if ($allowedAreas === null) {
                $locations = [];
            } else {
                $locations = $this->locationRepo->getLocationsForRacesInAreas($allowedAreas);
            }

            $form->add('location', EntityType::class, [
                'class' => Location::class,
                'label' => 'Startort',
                'placeholder' => 'Erst Areale auswählen',
                'choice_label' => 'name',
                'choices' => $locations,
            ]);
            $form->add('deathLocation', EntityType::class, [
                'class' => Location::class,
                'label' => 'Todesort',
                'placeholder' => 'Erst Areale auswählen',
                'choice_label' => 'name',
                'choices' => $locations,
            ]);
        };

        $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($formModifier) {
            // this would be your entity, i.e. SportMeetup
            $data = $event->getData();

            $formModifier($event->getForm(), $data->getAllowedAreas());
        }
        );

        $builder->get('allowedAreas')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
            // It's important here to fetch $event->getForm()->getData(), as
            // $event->getData() will get you the client data (that is, the ID)
            $areas = $event->getForm()->getData();

            // since we've added the listener to the child, we'll have to pass on
            // the parent to the callback functions!
            $formModifier($event->getForm()->getParent(), $areas);
        }
        );
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Race::class,
        ]);
    }

}
