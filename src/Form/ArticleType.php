<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Tag;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Titre de l'article",
                'attr' => ['placeholder' => "Mettez le titre de l'article"]
            ])
            ->add('slug', TextType::class, [
                'label' => "L'url de l'article",
                'attr' => ['placeholder' => "Ce champ n'est pas obligatoire.
                L'url se met automatiquement sauf si vous voulez la personalisée"],
                'required' => false
            ])
            ->add('imageFile',  FileType::class, [
                'label' => 'Téléchargez une image pour votre article',
                'data_class' => null,
                'required' => false,
                'attr' => ['placeholder' => 'choisir une image'],
                'mapped' => true,
                'constraints' => [
                    new Image([
                        'maxSize' => '3M',
                        'mimeTypes' => [
                            'image/jpg', 'image/jpeg', 'image/png', 'image/bmp'
                        ]
                    ])
                ]
            ])
            ->add('content', CKEditorType::class, [
                'config' => array(
                    'toolbar' => 'standard',
                    'input_sync' => true,
                    'uiColor' => '#f7f7f7',
                ),
                'label' => "Contenu de l'article",
            ])
            ->add('refDescription', TextType::class, [
                'label' => "Choississez la description pour le référencement de l'article",
                'required' => false,
            ])
            ->add('tags', EntityType::class, [
                'label' => "Choississez le tag correspondant à l'article",
                'required' => false,
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
