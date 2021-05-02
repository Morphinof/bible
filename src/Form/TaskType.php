<?php

declare(strict_types=1);

namespace Bible\Form;

use Bible\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'field',
                    'placeholder' => 'Title',
                ],
            ])
            ->add('issue', NumberType::class, [
                'attr' => [
                    'class' => 'field',
                    'placeholder' => 'Issue ID',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'choices' => Task::FORM_STATUSES,
                'attr' => [
                    'class' => 'ui fluid dropdown',
                ],
            ])
            ->add('notes', TextareaType::class, [
                'attr' => [
                    'class' => 'field textarea-editor',
                    'placeholder' => '## Markdown language',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
