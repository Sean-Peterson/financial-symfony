<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetTkype;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('income', NumberType::class)
        ->add('city', ChoiceType::class, array(
            'choices'  => array(
              "New York" => 'New-York',
              "Portland" => 'Portland',
              "Los Angeles" => 'Los-Angeles',
              "Denver" => 'Denver',
              "Austin" => 'Austin',
              "Nashville" => 'Nashville',
              "Seattle" => 'Seattle',
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
