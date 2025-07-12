<?php

namespace App\Controller\Api;

use App\Entity\Task;
use App\Enum\TaskMode;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Security\ApiAccessChecker;
use App\Service\EnumService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/task', name: 'api_task_')]
final class ApiTaskController extends AbstractController
{
    public function __construct(private ApiAccessChecker $accessChecker) {}

    #[Route('/get-all', name: 'get_all', methods: ['GET'])]
    public function getAll(TaskRepository $taskRepository): Response
    {
        return $this->render('api_task/index.html.twig', [
            'tasks' => $taskRepository->findAll(),
        ]);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, EnumService $enumServ): JsonResponse|Response
    {
        // Security-checks
        $csrfToken = $request?->headers->get('X-CSRF-TOKEN');
        try {
            $this->accessChecker->ensureCsrfValid('task_api', $csrfToken);
        } catch (BadRequestHttpException $e) {
            return $this->json([
                'success' => false,
                'errors'  => $e->getMessage()
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $task = new Task();
        $form = $this->createForm(TaskType::class, $task, [
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if (! $form->isSubmitted() || ! $form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true) as $err) {
                $errors[] = [
                    'field'   => $err->getOrigin()->getName(),
                    'message' => $err->getMessage(),
                ];
            }
            return new JsonResponse(
                [
                    'success' => false,
                    'errors' => $errors
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        // Fill default task-data, persist and return
        $task->setUserRef($this->getUser());
        $task->setIsCompleted(false);
        $task->setMode($enumServ->enumFromString('user', TaskMode::class));
        $em->persist($task);
        $em->flush();

        return new JsonResponse([
            'success' => true,
            'id'   => $task->getId(),
            'mode' => $task->getMode()->value,
        ], JsonResponse::HTTP_CREATED);
    }

    #[Route('/get/{id}', name: 'get_by_id', methods: ['GET'])]
    public function getById(Task $task): Response
    {
        return $this->render('api_task/show.html.twig', [
            'success' => false,
            'task' => $task,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        // Security-checks
        $csrfToken = $request?->headers->get('X-CSRF-TOKEN');
        try {
            $this->accessChecker->ensureCsrfValid('task_api', $csrfToken);
        } catch (BadRequestHttpException $e) {
            return $this->json([
                'success' => false,
                'errors'  => $e->getMessage()
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

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
        // Security-checks
        $csrfToken = $request?->headers->get('X-CSRF-TOKEN');
        try {
            $this->accessChecker->ensureCsrfValid('task_api', $csrfToken);
        } catch (BadRequestHttpException $e) {
            return $this->json([
                'success' => false,
                'errors'  => $e->getMessage()
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->redirectToRoute('api_todo_get_all', [], Response::HTTP_SEE_OTHER);
    }
}
