<?php

declare(strict_types=1);

namespace Bible\Form;

use Bible\Entity\Category;
use Bible\Entity\Note;
use Bible\Entity\Tag;
use Bible\Repository\CategoryRepository;
use Bible\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteType extends AbstractType
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
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'query_builder' => static function (CategoryRepository $r) {
                    return $r->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'attr' => [
                    'class' => 'ui fluid dropdown',
                    'placeholder' => 'Categories',
                ],
                'multiple' => true,
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'query_builder' => static function (TagRepository $r) {
                    return $r->createQueryBuilder('t')
                        ->orderBy('t.name', 'ASC');
                },
                'attr' => [
                    'class' => 'ui fluid dropdown',
                    'placeholder' => 'Tags',
                ],
                'multiple' => true,
            ])
            ->add('content', TextareaType::class, [
                'attr' => [
                    'class' => 'textarea-editor',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Note::class,
        ]);
    }
}
