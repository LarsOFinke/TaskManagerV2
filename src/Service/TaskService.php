<?php

namespace App\Service;

use App\Entity\Task;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskService extends AbstractController
{

    public function mapTasks(Collection $tasks): array
    {
        return $tasks
            ->map(fn(Task $t): array => [
                'id'            => $t->getId(),
                'title'         => $t->getTitle(),
                'description'   => $t->getDescription(),
                'isCompleted'   => $t->isCompleted(),
                'topic'         => $t->getTopicIDRef()?->getName(),
            ])
            ->toArray();
    }
}
