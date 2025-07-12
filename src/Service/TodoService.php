<?php

namespace App\Service;

use App\Entity\Todo;
use Doctrine\Common\Collections\Collection;

class TodoService
{
    public function mapTodos(Collection $todos)
    {
        return $todos->map(fn(Todo $t): array => [
            'id'        => $t->getId(),
            'text'      => $t->getText(),
            'taskId'    => $t->getTaskIDRef()->getId(),
        ]);
    }
}
