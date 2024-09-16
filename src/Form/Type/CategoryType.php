<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('name', TextType::class, [ 'label' => 'Kategori adı' ])
            ->add('description', TextType::class, [ 'label' => 'Açıklama' ])
            ->add('submit', SubmitType::class, [ 'label' => 'Ekle' ]);
    }
}