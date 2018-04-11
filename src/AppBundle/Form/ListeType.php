<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use AppBundle\Form\CitoyenType;
use AppBundle\Form\CommuneType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceTypeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('citoyen', CitoyenType::class)
            ->add('commune', CommuneType::class)
            ->add('blocked', ChoiceType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Liste',
        ]);
    }
}