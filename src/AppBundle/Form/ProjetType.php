<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class ProjetType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('sujet', TextType::class)
        ->add('contenu', TextareaType::class)
        ->add('dateDebut', DateType::class)
        ->add('duree', TextType::class)
        ->add('done', ChoiceType::class, array(
                'choices'  => array(
                    'En cours'=>null,
                    'Interrompu' => false,
                    'Terminé' => true,
                    )))
        ->add('image', FileType::class, [ 
            'data_class' => null
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Projet'
        ));
    }

  


}
