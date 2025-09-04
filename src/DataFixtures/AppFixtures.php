<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\Tag;
use App\Entity\Task;
use App\Entity\TaskDetail;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // --- Create a Project ---
        $project = new Project();
        $project->setName('Symfony Learning Project');
        $manager->persist($project);

        // --- Create some Tags ---
        $urgent = new Tag();
        $urgent->setName('Urgent');
        $manager->persist($urgent);

        $feature = new Tag();
        $feature->setName('Feature');
        $manager->persist($feature);

        // --- Create a Task ---
        $task = new Task();
        $task->setTitle('Build Task Entity');
        $task->setDescription('Learn Doctrine relationships step by step.');
        $task->setStatus('pending');
        $task->setDueDate(new \DateTimeImmutable('+7 days'));
        $task->setProject($project); // ManyToOne: belongs to Project
        $task->addTag($urgent);      // ManyToMany
        $task->addTag($feature);

        // --- Add TaskDetail (OneToOne) ---
        $detail = new TaskDetail();
        $detail->setPriority(1);
        $detail->setEstimatedHours(5);
        $detail->setTask($task);

        $manager->persist($task);
        $manager->persist($detail);

        // --- Another Task ---
        $task2 = new Task();
        $task2->setTitle('Setup PostgreSQL in Docker');
        $task2->setDescription('Run database in Docker and connect via Symfony.');
        $task2->setStatus('in_progress');
        $task2->setDueDate(new \DateTimeImmutable('+3 days'));
        $task2->setProject($project);
        $task2->addTag($feature);

        $manager->persist($task2);

        // Save all
        $manager->flush();
    }
}