<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
    #[Route('/task/create', name: 'task_create')]
    public function create(TaskRepository $taskRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        $project = $entityManagerInterface->getRepository(Project::class)->find(1);
        $task = new Task();
        $task->setTitle('Controller-created task');
        $task->setDescription('This task was created via TaskController.');
        $task->setStatus('pending');
        $task->setDueDate(new \DateTimeImmutable('+2 days'));
        $task->setProject($project);

        $taskRepository->save($task, true);
        return new Response("Task created with ID: " . $task->getId());
    }

    #[Route('/task/{id}', name: 'task_show', requirements: ['id' => '\d+'])]
    public function show(TaskRepository $taskRepository, int $id): Response
    {
        $task = $taskRepository->find($id);

        if (!$task) {
            return new Response("Task not found!", 404);
        }

        return new Response("Task: " . $task->getTitle() . " | Status: " . $task->getStatus());
    }

    #[Route('/task/{id}/update', name: 'task_update')]
    public function update(TaskRepository $taskRepository, int $id): Response
    {
        $task = $taskRepository->find($id);

        if (!$task) {
            return new Response("Task not found!", 404);
        }

        $task->setStatus('done');
        $taskRepository->save($task, true);

        return new Response("Task {$task->getId()} updated to DONE!");
    }

    #[Route('/task/{id}/delete', name: 'task_delete')]
    public function delete(TaskRepository $taskRepository, int $id): Response
    {
        $task = $taskRepository->find($id);

        if (!$task) {
            return new Response("Task not found!", 404);
        }

        $taskRepository->remove($task, true);

        return new Response("Task {$id} deleted!");
    }

    #[Route("/task/overdue", name:'task_overdue')]
    public function overdue(TaskRepository $taskRepository): Response
    {
        $task = $taskRepository->findOverdueTasks();
        return new Response('Overdue tasks: '. count($task));
    }

    #[Route("/task/project/{id}", name:"task_by_project")]
    public function byProject(TaskRepository $taskRepository, int $id): Response
    {
        $tasks = $taskRepository->findByProject($id);
        return new Response("Project $id has ". count($tasks) . " tasks");
    }

    #[Route('/task/tag/{name}', name: 'task_by_tag')]
    public function byTag(TaskRepository $taskRepository, string $name): Response
    {
        $tasks = $taskRepository->findByTagName($name);
        return new Response("Tasks with tag '$name': " . count($tasks));
    }
}
