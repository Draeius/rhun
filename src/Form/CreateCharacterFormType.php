<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of CreateCharacterFormType
 *
 * @author Draeius
 */
class CreateCharacterFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', TextType::class, ['label' => 'Name'])
                ->add('city', ChoiceType::class, ['label' => 'Stadt',
                    'choices' => [
                        'placeholder' => 'Wähle eine Stadt',
                        'nelaris' => 'Nelaris',
                        'lerentia' => 'Lerentia',
                        'pyra' => 'Pyra',
                        'manosse' => 'Manosse',
                        'underworld' => 'Unterwelt'
                    ]
                ])
                ->add('race', ChoiceType::class, ['label' => 'Rasse'])
                ->add('gender', ChoiceType::class, ['label' => 'Geschlecht', 'expanded' => true, 'multiple' => false,
                    'choices' => [
                        'Männlich' => true,
                        'Weiblich' => false
                    ]
        ]);
    }

}
