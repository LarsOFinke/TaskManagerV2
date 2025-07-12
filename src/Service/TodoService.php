<?php

namespace App\Service;

use Error;
use App\Entity\Task;
use App\Entity\Todo;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class TodoService
{
    public function __construct(private EntityManagerInterface $em) {}

    public function mapTodos(Collection $todos): array
    {
        return $todos->map(fn(Todo $t): array => [
            'taskId'        => $t->getTaskIDRef()->getId(),
            'id'            => $t->getId(),
            'text'          => $t->getText(),
            'isCompleted'   => $t->isCompleted(),
        ])->toArray();
    }

    public function createTodoList(array $todos, Task $task): void
    {
        try {
            foreach ($todos as $t) {
                $todo = new Todo();
                $todo->setTaskIDRef($task);
                $todo->setText($t['text']);
                $todo->setIsCompleted(false);
                $this->em->persist($todo);
            }
        } catch (Error $e) {
            throw new Error('Couldnt create task: ' . $e);
        }
    }
}
