<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('FirstName')

            /*
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'agent-one' => 'ROLE_AGENT_ONE',
                    'agent-two' => 'ROLE_AGENT_TWO',
                ],
            ])
            */
            ->add('roles', CollectionType::class, [
                'entry_type'   => ChoiceType::class,
                'entry_options'  => [
                    'label' => false,
                    'choices' => [
                        'Agent One' => 'ROLE_AGENT_ONE',
                        'Agent Two' => 'ROLE_AGENT_TWO',
                    ],
                ],
            ])

            ->add('password')
            ->add('LastName')
            ->add('email')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
