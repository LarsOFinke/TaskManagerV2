<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\Topic;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
    public function __construct(
        private EntityManagerInterface $em
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('userRef', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('TopicIDRef', EntityType::class, [
                'class'        => Topic::class,
                'choice_label' => 'name',    // what shows up in a Twig form, not relevant here
                'choice_value' => 'id',      // use the entity’s ID in submitted data
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'csrf_protection'    => false,  // since it’s an API
            'allow_extra_fields' => true,
        ]);
    }
}
