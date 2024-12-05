<?php

namespace App\Form;

use App\Entity\Contact;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,
                [
                    'label' => 'Votre nom',
                    'constraints'
                    => [
                        new Assert\NotBlank(),
                    ],
                ])
            ->add('email', EmailType::class,
                [
                    'label' => 'Votre email',
                    'constraints'
                    => [
                        new Assert\NotBlank(),
                        new Assert\Email(),
                    ],
                ])
            ->add('destination', EntityType::class, [
                'label' => 'Administrateur à contacter',
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er  ->createQueryBuilder('u')
                                ->where('u.roles LIKE :role')
                                ->setParameter('role','%"ROLE_ADMIN"%');
                },
                'choice_label' => function (User $user){
                    return $user->getFirstName() . ' ' . $user->getLastName();
                },
            ])->add('subject', TextType::class, [
                'label' => 'Votre sujet',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 5, 'max' => 1000000]),
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Votre message',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 20, 'max' => 255]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
