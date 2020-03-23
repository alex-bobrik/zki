<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\TestQuestion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('questions', EntityType::class, [
                'class' => Question::class,
                'choice_label' => 'name',
                'label' => 'Вопрос',
                'mapped' => true,
                'multiple' => false,
                'attr' => ['class'=> 'form-control shadow-none']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TestQuestion::class,
        ]);
    }
}
