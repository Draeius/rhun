<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Ein Form um einen neuen Account anzulegen.
 *
 * @author Draeius
 */
class CreateAccountFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('username', TextType::class, ['label' => ' ', 'required' => true])
                ->add('password', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options' => array('label' => ' '),
                    'second_options' => array('label' => ' '),)
                )
                ->add('email', EmailType::class, ['label' => ' ', 'required' => true])
                ->add('birthday', BirthdayType::class, ['label' => ' ', 'required' => false]);
    }

}
