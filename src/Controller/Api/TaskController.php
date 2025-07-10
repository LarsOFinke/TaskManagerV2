<?php
// src/Controller/Api/TaskController.php
namespace App\Controller\Api;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tasks', name: 'api_tasks_')]
class TaskController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em) {}

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $tasks = $this->em->getRepository(Task::class)->findAll();

        // Serialisierung per Serializer-Component oder manuell map­pen
        $data = array_map(fn(Task $t) => [
            'id'          => $t->getId(),
            'title'       => $t->getTitle(),
            'isCompleted' => $t->isCompleted(),
        ], $tasks);

        return $this->json($data);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Task $task): JsonResponse
    {
        return $this->json([
            'id'    => $task->getId(),
            'title' => $task->getTitle(),
            // …
        ]);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);
        // Validierung via Validator-Component oder manuell
        $task = new Task();
        $task->setTitle($payload['title'] ?? '');
        $this->em->persist($task);
        $this->em->flush();

        return $this->json(
            ['id' => $task->getId()],
            JsonResponse::HTTP_CREATED
        );
    }

    // Weitere Endpunkte (update, delete) analog hinzufügen…
}
