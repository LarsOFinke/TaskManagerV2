<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\Topic;
use App\Entity\User;
use App\Enum\TaskMode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('TopicIDRef', TextType::class, [
                'mapped' => true,
            ])
        ;

        // transform Topic <-> string(name)
        $builder->get('TopicIDRef')
            ->addModelTransformer(new CallbackTransformer(
                // Entity → string for “view”
                function (?Topic $topic) {
                    return $topic?->getName() ?? '';
                },
                // string → Entity for “model”
                function (string $topicName) {
                    $topic = $this->em
                        ->getRepository(Topic::class)
                        ->findOneBy(['name' => $topicName]);

                    if (! $topic) {
                        throw new TransformationFailedException(
                            sprintf('Topic "%s" not found.', $topicName)
                        );
                    }

                    return $topic;
                }
            ))
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
