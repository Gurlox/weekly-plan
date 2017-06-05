<?php

namespace PlanBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PlanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('task', TextType::class, [
                'label' => false
            ])
            ->add('day', ChoiceType::class, [
                'choices'       => [
                    'Poniedziałek'  => 1,
                    'Wtorek'        => 2,
                    'Środa'         => 3,
                    'Czwartek'      => 4,
                    'Piątek'        => 5,
                    'Sobota'        => 6,
                    'Niedziela'     => 7
                ],
                'invalid_message' => 'Niepoprawny format dnia'
            ])
            ->add('start_time', TimeType::class, [
                'widget' => 'single_text'
            ])
            ->add('end_time', TimeType::class, [
                'widget' => 'single_text'
            ])
        ;
    }
}
