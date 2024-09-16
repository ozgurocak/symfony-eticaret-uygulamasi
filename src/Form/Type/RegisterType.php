<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegisterType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('username', TextType::class, [ 'label' => 'Kullanıcı adı' ])
            ->add('password', PasswordType::class, [ 'label' => 'Şifre' ])
            ->add('password_repeat', PasswordType::class, [ 'label' => 'Şifre tekrar' ])
            ->add('submit', SubmitType::class, [ 'label' => 'Kayıt ol' ]);
    }
}