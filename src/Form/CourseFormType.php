<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\CourseCategory;
use App\Entity\CourseLevel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CourseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 5, 'max' => 100]),
                ],
            ])
            ->add('smallDescription', TextareaType::class, [
                'label' => 'Description courte',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 20, 'max' => 255]),
                ],
            ])
            ->add('fullDescription', TextareaType::class, [
                'label' => 'Description complète',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 100]),
                ],
            ])
            ->add('duration', TextType::class, [
                'label' => 'Durée',
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix',
                'currency' => 'EUR',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Positive(),
                ],
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image du cours',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG ou GIF).',
                        'maxSizeMessage' => 'L\'image ne peut pas dépasser 5 Mo.',
                    ]),
                ],
            ])
            ->add('programFile', VichFileType::class, [
                'label' => 'Programme PDF',
                'required' => false,
                'allow_delete' => false,
                'download_uri' => false,
                'asset_helper' => true,
                'attr' => ['accept' => '.pdf'],
                'constraints' => [
                    new Assert\File([
                        'maxSize' => '10M',
                        'mimeTypes' => ['application/pdf'],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF valide.',
                        'maxSizeMessage' => 'Le fichier ne peut pas dépasser 10 Mo.',
                    ]),
                ],
            ])
            ->add('isPublished', CheckboxType::class, [
                'label' => 'est publié',
                'required' => false,
            ])
            ->add('categoryId', EntityType::class, [
                'label' => 'Catégorie',
                'class' => CourseCategory::class,
                'choice_label' => 'name',
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('levelId', EntityType::class, [
                'label' => 'Niveau recommandé',
                'class' => CourseLevel::class,
                'choice_label' => 'name',
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
