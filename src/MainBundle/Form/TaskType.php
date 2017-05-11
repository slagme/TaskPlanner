<?php

namespace MainBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
            ->add('description')
            ->add('deadline', DateTimeType::class, ['years' => [2017, 2018, 2019, 2020]])
            ->add('priority', ChoiceType::class,
                ['choices' => (['ASAP' => 'ASAPvalue', 'May Wait' => 'May Wait', 'Urgent'=> 'Urgent']),
                    'choices_as_values' => false,
                ])
            ->add('category', EntityType::class,
                ['class' => 'MainBundle\Entity\Category', 'choice_label' => 'name']);
//
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MainBundle\Entity\Task'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'mainbundle_task';
    }


}
