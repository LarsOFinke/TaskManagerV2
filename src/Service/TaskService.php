<?php

namespace App\Service;

use App\Entity\Task;
use Doctrine\Common\Collections\Collection;

class TaskService
{
    public function __construct(private TopicService $topicService, private TodoService $todoService) {}

    public function mapTasks(Collection $tasks): array
    {
        return $tasks
            ->map(fn(Task $t): array => [
                'id'            => $t->getId(),
                'isCompleted'   => $t->isCompleted(),
                'topic'         => $this->topicService->mapTopic($t->getTopicIDRef()),
                'title'         => $t->getTitle(),
                'description'   => $t->getDescription(),
                'todoList'      => $this->todoService->mapTodos($t->getTodos())
            ])
            ->toArray();
    }
}
