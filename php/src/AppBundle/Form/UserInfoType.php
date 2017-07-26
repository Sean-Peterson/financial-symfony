<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetTkype;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('income', TextType::class)
        ->add('city', ChoiceType::class, array(
            'choices'  => array(
                'New York' => 'ny',
                'Portland' => 'or',
                'Los Angeles' => 'ca',
            ),
        ))
        ->add('marital_status', ChoiceType::class, array(
            'choices'  => array(
                'Single' => 'single',
                'Married' => 'married',
            ),
        ))
        ->add('submit', SubmitType::class, array('label' => 'Submit'));
    }
}
