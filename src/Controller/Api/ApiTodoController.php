<?php

namespace App\Controller\Api;

use App\Entity\Todo;
use App\Form\TodoType;
use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/todo', name: 'api_todo_')]
final class ApiTodoController extends AbstractController
{
    #[Route('/get-all', name: 'get_all', methods: ['GET'])]
    public function getAll(TodoRepository $todoRepository): Response
    {
        return $this->render('api_todo/index.html.twig', [
            'todos' => $todoRepository->findAll(),
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $todo = new Todo();
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($todo);
            $entityManager->flush();

            return $this->redirectToRoute('api_todo_get_all', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('api_todo/new.html.twig', [
            'todo' => $todo,
            'form' => $form,
        ]);
    }

    #[Route('/get/{id}', name: 'get_by_id', methods: ['GET'])]
    public function getById(Todo $todo): Response
    {
        return $this->render('api_todo/show.html.twig', [
            'todo' => $todo,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Todo $todo, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('api_todo_get_all', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('api_todo/edit.html.twig', [
            'todo' => $todo,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Todo $todo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$todo->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($todo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('api_todo_get_all', [], Response::HTTP_SEE_OTHER);
    }
}
