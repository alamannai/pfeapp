<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use AppBundle\Form\CitoyenType;
use AppBundle\Form\ProjetType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('projet', ProjetType::class)
            ->add('citoyen', CitoyenType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Vote',
        ]);
    }
}