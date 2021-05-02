<?php

namespace Bible\Controller;

use Bible\Entity\Task;
use Bible\Form\TaskType;
use Bible\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/task/save/{task}', name: 'bible_task_save', defaults: ['task' => null])]
    public function save(Request $request, EntityManagerInterface $manager, ?Task $task): Response|JsonResponse
    {
        $this->isAllowed($task);
        $form = $this->createForm(TaskType::class, $task, [
            'attr' => [
                'data-codemirror-id' => 'task_'.($task !== null ? $task->getId() : 0),
                'class' => 'ui form',
            ]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Task $task */
            $task = $form->getData();
            $task->setOwner($this->getUser());

            $manager->persist($task);
            $manager->flush();

            return new JsonResponse('bible.task.saved', Response::HTTP_CREATED);
        }

        return $this->render('task/form.html.twig', ['form' => $form->createView()]);
    }

    #[Route('/task/list', name: 'bible_task_list')]
    public function list(TaskRepository $taskRepository): Response
    {
        $tasks = $taskRepository->findBy(['owner' => $this->getUser()]);

        return $this->render('task/list.html.twig', ['tasks' => $tasks]);
    }

    #[Route('/task/delete/{task}', name: 'bible_task_delete')]
    public function delete(Task $task, EntityManagerInterface $manager): JsonResponse
    {
        $this->isAllowed($task);
        $manager->remove($task);
        $manager->flush();

        return new JsonResponse('bible.task.deleted');
    }

    #[Route('/task/{task}/save-field/{field}', name: 'bible_task_save_field', methods: ['POST'])]
    public function saveField(EntityManagerInterface $manager, Task $task, string $field, Request $request): JsonResponse
    {
        $this->isAllowed($task);

        $value = $request->get('value');
        if ($value === null) {
            return new JsonResponse('Empty value', Response::HTTP_BAD_REQUEST);
        }

        $response = match ($field) {
            'issue' => is_numeric($value) ? $task->setIssue((int)substr($value, 0, 10)) : new JsonResponse('Issue must be a number', Response::HTTP_BAD_REQUEST),
            'status' => $task->setStatus($value),
            'notes' => $task->setNotes($value),
            default => new JsonResponse(sprintf('Unsupported field "%s"', $field), Response::HTTP_BAD_REQUEST)
        };

        if ($response instanceof JsonResponse) {
            return $response;
        }

        $manager->flush();

        return new JsonResponse('bible.task.updated');
    }

    #[Route('/task/{task}', name: 'bible_task')]
    public function task(Task $task): Response
    {
        $this->isAllowed($task);
        return $this->render('task/task.html.twig', ['task' => $task]);
    }

    private function isAllowed(?Task $task): void
    {
        if ($task !== null && $task->getOwner() !== $this->getUser()) {
            throw new RuntimeException('bible.unauthorized', Response::HTTP_UNAUTHORIZED);
        }
    }
}
