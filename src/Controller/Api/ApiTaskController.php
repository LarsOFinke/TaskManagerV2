<?php

namespace App\Controller\Api;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/task', name: 'api_task_')]
final class ApiTaskController extends AbstractController
{
    #[Route('/get-all', name: 'get_all', methods: ['GET'])]
    public function getAll(TaskRepository $taskRepository): Response
    {
        return $this->render('api_task/index.html.twig', [
            'tasks' => $taskRepository->findAll(),
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('api_todo_get_all', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('api_task/new.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/get/{id}', name: 'get_by_id', methods: ['GET'])]
    public function getById(Task $task): Response
    {
        return $this->render('api_task/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('api_todo_get_all', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('api_task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('api_todo_get_all', [], Response::HTTP_SEE_OTHER);
    }
}
