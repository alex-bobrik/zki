<?php

namespace App\Form;

use App\Entity\Lection;
use App\Entity\Razdel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Название лекции',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('razdel', EntityType::class, [
                'class' => Razdel::class,
                'label' => 'Раздел',
                'choice_label' => 'name',
                'mapped' => true,
                'multiple' => false,
                'attr' => ['class'=> 'form-control']
            ])
            ->add('videoLink', TextType::class, [
                'label' => 'Ссылка на видео',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('materials', CollectionType::class, [
                'entry_type' => MaterialType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'data_class' => null,
                'by_reference' => false,
                'required' => true,
                'label' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Сохранить',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Lection::class,
        ]);
    }
}
