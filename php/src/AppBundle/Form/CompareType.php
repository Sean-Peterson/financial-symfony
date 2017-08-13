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

class CompareType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('income', NumberType::class)
        ->add('city', ChoiceType::class, array(
            'choices'  => array(
              "Austin" => 'Austin',
              "Boston" => 'Boston',
              "Chicago" => 'Chicago',
              "Cleveland" => 'Cleveland',
              "Dallas" => 'Dallas',
              "Denver" => 'Denver',
              "Houston" => 'Houston',
              "Indianapolis" => 'Indianapolis',
              "Kansas City" => 'Kansas-City',
              "Los Angeles" => 'Los-Angeles',
              "Miami" => 'Miami',
              "Minneapolis" => 'Minneapolis',
              "Nashville" => 'Nashville',
              "New Orleans" => 'New-Orleans',
              "New York" => 'New-York',
              "Oklahoma City" => 'Oklahoma-City',
              "Philadelphia" => 'Philadelphia',
              "Phoenix" => 'Phoenix',
              "Pittsburgh" => 'Pittsburgh',
              "Portland" => 'Portland',
              "Sacramento" => 'Sacramento',
              "San Diego" => 'San-Diego',
              "San Francisco" => 'San-Francisco',
              "San Jose" => 'San-Jose',
              "Seattle" => 'Seattle',
              "Washington D.C." => 'Washington',
            ),
        ))
        ->add('marital_status', ChoiceType::class, array(
            'choices'  => array(
                'Single' => 'single',
                'Married' => 'married',
            ),
        ))
        ->add('income_second', NumberType::class)
        ->add('city_second', ChoiceType::class, array(
            'choices'  => array(
              "Austin" => 'Austin',
              "Boston" => 'Boston',
              "Chicago" => 'Chicago',
              "Cleveland" => 'Cleveland',
              "Dallas" => 'Dallas',
              "Denver" => 'Denver',
              "Houston" => 'Houston',
              "Indianapolis" => 'Indianapolis',
              "Kansas City" => 'Kansas-City',
              "Los Angeles" => 'Los-Angeles',
              "Miami" => 'Miami',
              "Minneapolis" => 'Minneapolis',
              "Nashville" => 'Nashville',
              "New Orleans" => 'New-Orleans',
              "New York" => 'New-York',
              "Oklahoma City" => 'Oklahoma-City',
              "Philadelphia" => 'Philadelphia',
              "Phoenix" => 'Phoenix',
              "Pittsburgh" => 'Pittsburgh',
              "Portland" => 'Portland',
              "Sacramento" => 'Sacramento',
              "San Diego" => 'San-Diego',
              "San Francisco" => 'San-Francisco',
              "San Jose" => 'San-Jose',
              "Washington D.C." => 'Washington',
            ),
        ))
        ->add('marital_status_second', ChoiceType::class, array(
            'choices'  => array(
                'Single' => 'single',
                'Married' => 'married',
            ),
        ))
        ->add('submit', SubmitType::class, array('label' => 'Submit'));
    }
}
