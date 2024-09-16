<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Category;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [ 'label' => 'Ürün adı' ])
            ->add('description', TextType::class, [ 'label' => 'Açıklama' ])
            ->add('stock', IntegerType::class, [ 'label' => 'Stok' ])
            ->add('price', IntegerType::class, [ 'label' => 'Fiyat' ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'label' => 'Kategori',
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true
            ])
            ->add('image', FileType::class, [
                'label' => 'Resim (PNG) seçin.',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5120k',
                        'mimeTypes' => ['image/png'],
                        'mimeTypesMessage' => 'Lütfen geçerli bir PNG dosyası seçiniz.'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [ 'label' => 'Ekle' ]);
    }
}