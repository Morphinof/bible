<?php

declare(strict_types=1);

namespace Bible\Form;

use Bible\Entity\Daily;
use Bible\Entity\Task;
use Bible\Repository\TaskRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DailyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tasks', EntityType::class, [
                'class' => Task::class,
                'query_builder' => static function (TaskRepository $r) {
                    return $r->createQueryBuilder('t')
                        ->where('t.status != :status')
                        ->setParameter('status', Task::STATUS_COMPLETE)
                        ->orderBy('t.title', 'ASC');
                },
                'attr' => [
                    'class' => 'ui fluid search dropdown',
                    'placeholder' => 'Categories',
                ],
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Daily::class,
        ]);
    }
}
