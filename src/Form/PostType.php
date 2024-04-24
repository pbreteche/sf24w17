<?php

namespace App\Form;

use App\Entity\Post;
use App\Enum\PostState;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('publishedAt', options: [
                'widget' => 'single_text',
            ])
            ->add('body')
            ->add('state', EnumType::class, [
                'placeholder' => $options['state_placeholder'] ? '--' : null,
                'class' => PostState::class,
            ])
            ->add('filedIn', options: [
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
            'state_placeholder' => true,
        ])
            ->setAllowedTypes('state_placeholder', 'bool')
        ;
    }
}
