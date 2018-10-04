<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Image;


class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', Type\FileType::class, array(
                'label' => 'Image',
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Télécharger',
                    'accept' => '.jpg, .jpeg, .png',
                ),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => Image::class,
        ]);
    }
}
