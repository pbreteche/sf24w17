<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\EqualTo;
use Symfony\Component\Validator\Constraints\NotBlank;

class DeleteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('confirmation', options: [
        'constraints' => [
            new NotBlank(),
            new EqualTo($options['confirmation_message']),
        ],
        'help' => sprintf($options['help'], $options['confirmation_message']),
        'help_html' => true,
    ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('help', 'La suppression de cette donnée est irréversible. <br>
        Saisissez le texte suivant pour confirmer :
                <span style="font-style: italic">%s</span>');
        $resolver->setRequired('confirmation_message');
    }
}
