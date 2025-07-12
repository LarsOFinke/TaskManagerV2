<?php

namespace App\Controller\Admin;

use App\Entity\Todo;
use App\Form\Todo1Type;
use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/todo')]
final class AdminTodoController extends AbstractController
{
    #[Route(name: 'app_admin_todo_index', methods: ['GET'])]
    public function index(TodoRepository $todoRepository): Response
    {
        return $this->render('admin_todo/index.html.twig', [
            'todos' => $todoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_todo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $todo = new Todo();
        $form = $this->createForm(Todo1Type::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($todo);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_todo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_todo/new.html.twig', [
            'todo' => $todo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_todo_show', methods: ['GET'])]
    public function show(Todo $todo): Response
    {
        return $this->render('admin_todo/show.html.twig', [
            'todo' => $todo,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_todo_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Todo $todo, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Todo1Type::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_todo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_todo/edit.html.twig', [
            'todo' => $todo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_todo_delete', methods: ['POST'])]
    public function delete(Request $request, Todo $todo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $todo->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($todo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_todo_index', [], Response::HTTP_SEE_OTHER);
    }
}
