<?php

namespace App\Controller;

use App\Entity\Task;
use App\Enum\TaskMode;
use App\Form\TaskFormType;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/task', name: 'app_task_')]
final class TaskController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(TaskService $taskService): Response
    {
        /** @var \App\Entity\User|null $user 
         */
        $user   = $this->getUser();
        $taskList = $taskService->mapTasks($user->getTasks());

        return $this->render('task/task_list.html.twig', [
            'header'   => 'My Task List',
            'taskList' => json_encode($taskList),
        ]);
    }

    #[Route('/form', name: 'form')]
    public function form(): Response
    {
        $task = new Task();
        $task->setMode(TaskMode::USER);
        $form = $this->createForm(TaskFormType::class, $task);
        return $this->render('task/task_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
