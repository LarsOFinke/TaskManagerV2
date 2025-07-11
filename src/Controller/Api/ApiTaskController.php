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
    public function __construct(private ApiAccessChecker $apiCtrl) {}
    #[Route('/get-all', name: 'get_all', methods: ['GET'])]
    public function getAll(TaskRepository $taskRepository): Response
    {
        if (!$this->apiCtrl->ensureCsrfValid('')) {
            throw new BadRequestHttpException('Access denied!');
        }

        return $this->render('api_task/index.html.twig', [
            'tasks' => $taskRepository->findAll(),
        ]);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, EnumService $enumServ): JsonResponse|Response
    {
        if (!$this->apiCtrl->ensureCsrfValid('task_api')) {
            throw new BadRequestHttpException('Invalid CSRF token!');
        }
        $data = json_decode($request->getContent(), true);



        $task = new Task();
        $form = $this->createForm(TaskType::class, $task, [
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
        $form->submit($data);

        // 3) Validate
        if (! $form->isSubmitted() || ! $form->isValid()) {
            $errors = [];
            foreach ($form->getErrors(true) as $err) {
                $errors[] = [
                    'field'   => $err->getOrigin()->getName(),
                    'message' => $err->getMessage(),
                ];
            }
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        }

        // 4) Persist and return
        $task->setUserRef($this->getUser());
        $task->setIsCompleted(false);
        $task->setMode($enumServ->enumFromString('user', TaskMode::class));
        $em->persist($task);
        $em->flush();

        return new JsonResponse([
            'id'   => $task->getId(),
            'mode' => $task->getMode()->value,
        ], JsonResponse::HTTP_CREATED);
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
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();
        }

        return $this->redirectToRoute('api_todo_get_all', [], Response::HTTP_SEE_OTHER);
    }
}
