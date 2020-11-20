<?php

namespace App\Form;

use App\Entity\Comments;
use App\Entity\Tickets;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class CommentsType extends AbstractType
{

    private $security;
    public function __construct(Security $security)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->security->getUser();
        $role=$user->getRoles();
        $builder
            ->add('commentContent', TextareaType::class);
            if ($role==['ROLE_AGENT_ONE']||$role==['ROLE_AGENT_TWO']||$role==['ROLE_AGENT_MANAGER']) {
                $builder->add('status', CollectionType::class, [
                    'entry_type' => ChoiceType::class,
                    'entry_options' => [
                        'label' => false,
                        'choices' => [
                            'PUBLIC' => 'PUBLIC',
                            'PRIVATE' => 'PRIVATE',
                        ],
                    ],
                ]);
            }

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }
}
