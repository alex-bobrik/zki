<?php

namespace App\Form;

use App\Entity\Lab;
use App\Entity\LabResult;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LabResultType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lab', EntityType::class, [
                'class' => Lab::class,
                'label' => 'Лабораторная',
                'choice_label' => 'name',
                'mapped' => true,
                'multiple' => false,
                'attr' => ['class'=> 'form-control shadow-none']
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'label' => 'Студент',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.username != :u_username')
                        ->setParameter('u_username','teacher');
                },
                'choice_label' => 'fullName',
                'mapped' => true,
                'multiple' => false,
                'attr' => ['class'=> 'form-control shadow-none']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Сохранить',
                'attr' => [
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LabResult::class,
        ]);
    }
}
