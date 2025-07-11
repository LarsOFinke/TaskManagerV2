<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\Todo;
use App\Enum\TaskCategory;
use App\Enum\TaskInterval;
use App\Enum\TaskMode;
use App\Enum\TaskPriority;
use App\Repository\TopicRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TaskService extends AbstractController
{

    private function serializeTask(Task $t, bool $fetch = false): array
    {
        return [
            'id'            => $t->getId(),
            'isOpen'        => $t->isCompleted(),
            'mode'          => $t->getMode()->value,
            // 'category'      => $t->getCategory()->value,
            // 'priority'      => $t->getPriority()->value,
            // 'deadlineDate'  => $t->getDeadlineDate()?->format('Y-m-d'),
            // 'deadlineTime'  => $t->getDeadlineTime(),
            // 'interval'      => $t->getInterval() === null ? null : $t->getInterval()->value,
            // 'startDate'     => $t->getStartDate()?->format('Y-m-d'),
            // 'remainingTime' => $t->getRemainingTime(),
            'title'         => $t->getTitle(),
            'description'   => $t->getDescription(),
            'UserIDRef'     => $t->getUserRef()?->getId(),
            // 'TeamIDRef'     => $t->getTeamRef()?->getId(),
            'editUrl'       => $this->generateUrl('app_tasks_edit', ['id' => $t->getId()]),
            'canEdit'       => true,
            'todoList'      => $t
                ->getTodos()            // Collection<Todos>
                ->map(fn(Todo $todo) => [
                    'id'     => $todo->getId(),
                    'text'   => $todo->getText(),
                    'isOpen' => $todo->isCompleted(),
                ])
                ->toArray(),
            $fetch ? 'topic' : 'TopicIDRef'    => $t->getTopicIDRef()->getName(),
        ];
    }



    // /**
    //  * @param Task $task          The Task we’re editing
    //  * @param int[] $keepIds       IDs of Todos to keep
    //  * @param string[] $newTexts   Texts of brand-new Todos
    //  */
    // private function syncTodos(Task $task, array $keepIds, array $todoTexts, array $newTexts): void
    // {
    //     // 1) Remove any Todo not in keepIds
    //     foreach ($task->getTodos() as $todo) {
    //         if (!in_array($todo->getId(), $keepIds, true)) {
    //             $task->removeTodos($todo);
    //         } else {
    //             // 1b) Update the text on the ones we keep
    //             if (isset($todoTexts[$todo->getId()])) {
    //                 $todo->setText($todoTexts[$todo->getId()]);
    //             }
    //         }
    //     }

    //     // 2) Add brand-new Todos
    //     foreach ($newTexts as $text) {
    //         $todo = new Todo();
    //         $todo
    //             ->setText($text)
    //             ->setIsOpen(true)
    //             ->setTaskIDRef($task)
    //         ;
    //         $task->addTodos($todo);
    //     }
    // }

    // private function updateTasksDatabase(Request $request, EntityManagerInterface $em, TopicRepository $topicsRepo, Task|null $task = null, bool $create = false): bool
    // {
    //     // Fetch data from the form-submission-request //
    //     $priority = $request->request->get('priority');
    //     $topicName = $request->request->get('topic');
    //     $category = $request->request->get('category');
    //     $deadlineDate = $request->request->get('deadlineDate');
    //     $deadlineTime = $request->request->get('deadlineTime');
    //     $interval = $request->request->get('interval');
    //     $startDate = $request->request->get('startDate');
    //     $title = $request->request->get('title');
    //     $description = $request->request->get('description');
    //     $topic = $topicsRepo->getTopicIdByName($topicName);
    //     if ($topic === null) {
    //         throw new BadRequestHttpException("Unknown topic “{$topicName}”");
    //     }



    //     // Create a new Task to save the already prepared data + for todos-id-reference //
    //     if ($create && $task === null) {
    //         $task = new Task();
    //         $task->setMode(TaskMode::USER);
    //     }
    //     $task->setTopicIDRef($topic);
    //     $task->setTitle($title);
    //     $task->setDescription($description);
    //     $task->setIsOpen(true);
    //     $task->setUserIDRef($this->getUser());
    //     $task->setTeamIDRef(null);

    //     $task->setPriority(
    //         $this->enumFromString($priority, TaskPriority::class)
    //     );
    //     $task->setCategory(
    //         $this->enumFromString($category, TaskCategory::class)
    //     );

    //     // Check if interval is set for more data-field-saves //
    //     if ($interval !== '') {
    //         $task->setInterval($this->enumFromString($interval, TaskInterval::class));
    //         if ($startDate !== '') {
    //             $task->setStartDate(new \DateTime($startDate));
    //         }
    //         if ($deadlineDate !== '') {
    //             $task->setDeadlineDate(new \DateTime($deadlineDate));
    //         }
    //         if ($deadlineTime !== '') {
    //             $task->setDeadlineTime($deadlineTime);
    //         }
    //     }

    //     $em->persist($task);    // Now the Task has an ID (auto-increment)

    //     if ($create) {
    //         $todoList = $request->request->all('todoList', []);
    //         if (!\is_array($todoList)) {
    //             $todoList = [];
    //         }
    //         foreach ($todoList as $todoText) {
    //             $todo = (new Todo())
    //                 ->setText($todoText)
    //                 ->setIsOpen(true)
    //                 ->setTaskIDRef($task);
    //             $em->persist($todo);    // Save the todos //
    //         }
    //     } else {
    //         $keepIds   = array_map('intval', $request->request->all('existingTodos', []));
    //         $todoTexts = $request->request->all('todoTexts', []);
    //         $newTexts  = $request->request->all('newTodos', []);

    //         $this->syncTodos($task, $keepIds, $todoTexts, $newTexts);
    //     }

    //     $em->flush();   // Save the todos in DB //

    //     return true;
    // }
}
