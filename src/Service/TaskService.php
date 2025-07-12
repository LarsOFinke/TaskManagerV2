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
                'title'       => $t->getTitle(),
                'description' => $t->getDescription(),
                'mode'        => $t->getMode(),
                'isCompleted' => $t->isCompleted(),
                'topic'       => $t->getTopicIDRef()?->getName(),
            ])
            ->toArray();
    }
}
