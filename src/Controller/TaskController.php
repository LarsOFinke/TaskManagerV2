<?php

namespace App\Controller;

use App\Entity\Task;
use App\Enum\TaskMode;
use App\Form\TaskFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/task', name: 'app_task_')]
final class TaskController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(): Response
    {
        /** @var \App\Entity\User|null $user */
        $user   = $this->getUser();
        if ($user === null) {
            throw new BadRequestHttpException('Bite anmelden!');
        }

        $tasks = $user->getTasks();
        $taskList = json_encode($tasks, JSON_THROW_ON_ERROR);

        return $this->render('task/tasks_list.html.twig', [
            'header'   => 'My Task List',
            'taskList' => $taskList,
        ]);
    }

    #[Route('/form', name: 'form')]
    public function form(): Response
    {
        /** @var \App\Entity\User|null $user */
        $user   = $this->getUser();
        if ($user === null) {
            throw new BadRequestHttpException('Bite anmelden!');
        }

        $task = new Task();
        $task->setMode(TaskMode::USER);
        $form = $this->createForm(TaskFormType::class, $task);
        return $this->render('tasks/tasks_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
