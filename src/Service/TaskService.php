<?php

namespace App\Service;

use Error;
use App\Entity\User;
use App\Entity\Task;
use App\Enum\TaskMode;
use App\Repository\TopicRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TaskService
{
    public function __construct(private EntityManagerInterface $em, private EnumService $enumServ, private TopicService $topicService, private TodoService $todoService, private TopicRepository $topicRepo) {}

    public function createNewTask(Request $request, User $user): void
    {
        try {
            $data = json_decode($request->getContent(), true);
            $task = new Task();
            $task->setUserRef($user);
            $task->setTopicIDRef($this->topicRepo->find($data['topic']));
            $task->setMode($this->enumServ->enumFromString('user', TaskMode::class));
            $task->setTitle($data['title']);
            $task->setDescription($data['description']);
            $task->setIsCompleted(false);
            $this->em->persist($task);
            $this->todoService->createTodoList($data['todoList'], $task);
            $this->em->flush();
        } catch (Error $e) {
            throw new Error('Couldnt create task: ' . $e);
        }
    }

    public function mapTasks(Collection $tasks): array
    {
        return $tasks
            ->map(fn(Task $t): array => [
                'id'            => $t->getId(),
                'isCompleted'   => $t->isCompleted(),
                'topic'         => $this->topicService->mapTopic($t->getTopicIDRef()),
                'title'         => $t->getTitle(),
                'description'   => $t->getDescription(),
            ])
            ->toArray();
    }
}
