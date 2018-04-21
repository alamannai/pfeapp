<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use AppBundle\Form\CitoyenType;
use AppBundle\Form\SondageType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sondage', SondageType::class)
            ->add('datep', DateType::class)
            ->add('type', ChoiceType::class)
            ->add('citoyen', CitoyenType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Participation',
        ]);
    }
}