<?php

namespace App\Form;

use App\Entity\Area;
use App\Form\DTO\CreateCharacterDTO;
use App\Form\EventSubscriber as Subscriber;
use App\Repository\AreaRepository;
use App\Repository\RaceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Description of CreateCharacterFormType
 *
 * @author Draeius
 */
class CreateCharacterType extends AbstractType {

    /**
     *
     * @var AreaRepository
     */
    private $areaRepo;

    /**
     *
     * @var RaceRepository
     */
    private $raceRepo;

    function __construct(AreaRepository $areaRepo, RaceRepository $raceRepo) {
        $this->areaRepo = $areaRepo;
        $this->raceRepo = $raceRepo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', TextType::class, ['label' => 'Name'])
                ->add('city', EntityType::class, [
                    'class' => Area::class,
                    'label' => 'Heimatstadt',
                    'placeholder' => 'Bitte auswählen',
                    'choice_label' => 'name',
                    'choice_value' => 'city',
                    'choices' => $this->areaRepo->getAreasAllowingRaces()
                ])
                ->add('gender', ChoiceType::class, ['label' => 'Geschlecht', 'expanded' => true, 'multiple' => false,
                    'choices' => [
                        'Männlich' => true,
                        'Weiblich' => false
                    ]
                ])
        ;

        $builder->addEventSubscriber(new Subscriber\PreSetData\AddRaceFieldSubscriber($this->raceRepo));
        $builder->get('city')->addEventSubscriber(new Subscriber\PostSubmit\AddRaceFieldSubscriber($this->raceRepo));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => CreateCharacterDTO::class,
            'allow_extra_fields' => true
                ]
        );
    }

}
