<?php

namespace App\Service;

use Error;
use App\Entity\Task;
use App\Entity\Todo;
use App\Repository\TaskRepository;
use App\Repository\TodoRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

class TodoService
{
    public function __construct(private EntityManagerInterface $em, private TaskRepository $taskRepo, private TodoRepository $todoRepo) {}

    public function getTodosForTask($taskId)
    {
        $todos = $this->taskRepo->find($taskId)->getTodos();
        return $this->mapTodos($todos);
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
            throw new Error('Couldnt create todo-list: ' . $e);
        }
    }

    public function mapTodos(Collection $todos): array
    {
        return $todos->map(fn(Todo $t): array => [
            'taskId'        => $t->getTaskIDRef()->getId(),
            'id'            => $t->getId(),
            'text'          => $t->getText(),
            'isCompleted'   => $t->isCompleted(),
        ])->toArray();
    }

    /** @todo \App\Entity\Todo */
    public function closeTodo(int $todoId): void
    {
        try {
            $todo = $this->todoRepo->find($todoId);
            if (!$todo) {
                throw new Error('Couldnt find todo: ' . $todoId);
            }
            $todo->setIsCompleted(true);
            $this->em->persist($todo);
            $this->em->flush();
        } catch (Error $e) {
            throw new Error('Couldnt close todo: ' . $e);
        }
    }

    public function openTodo(int $todoId): void
    {
        try {
            $todo = $this->todoRepo->find($todoId);
            if (!$todo) {
                throw new Error('Couldnt find todo: ' . $todoId);
            }
            $todo->setIsCompleted(false);
            $this->em->persist($todo);
            $this->em->flush();
        } catch (Error $e) {
            throw new Error('Couldnt close todo: ' . $e);
        }
    }
}
