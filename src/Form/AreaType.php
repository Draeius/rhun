<?php

namespace App\Form;

use App\Entity\Area;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AreaType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', TextType::class, ['label' => 'Name'])
                ->add('coloredName', TextType::class, ['label' => 'bunter Name'])
                ->add('city', TextType::class, ['label' => 'Stadt'])
                ->add('description', TextareaType::class, ['label' => 'Beschreibung'])
                ->add('deadAllowed', CheckboxType::class, ['label' => 'Tote erlauben'])
                ->add('raceAllowed', CheckboxType::class, ['label' => 'Rassen können die als Heimatareal haben'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Area::class,
        ]);
    }

}
