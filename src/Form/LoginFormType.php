<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Description of LoginForm
 *
 * @author Draeius
 */
class LoginFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('username', null, ['label' => 'Name'])
                ->add('password', PasswordType::class, ['label' => 'Passwort']);
    }

}
