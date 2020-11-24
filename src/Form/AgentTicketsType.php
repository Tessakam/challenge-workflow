<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Tickets;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgentTicketsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder


    //->add('ticketStatus',ChoiceType::class, [
    //                'choices' => ['in progress'=>0,'second line'=>1,'close'=>2
    //                ],
    //            ])

            ->add('ticketPriority', ChoiceType::class, [
                'choices' => ['default' => 0, 'urgent' => 1, 'emergency' => 2
                ],
            ])
            ->add('assignedTo', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (UserRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.FirstName', 'ASC');
                },
                'choice_label' => 'FirstName',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tickets::class,
        ]);
    }
}