<?php

namespace App\Repository;

use App\Entity\Task;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

//    /**
//     * @return Task[] Returns an array of Task objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Task
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findOverdueTasks(): array
    {
        return $this->createQueryBuilder('t')
        ->andWhere('t.dueDate < :now')
        ->andWhere('t.status != :done')
        ->setParameter('now', new DateTime())
        ->setParameter('done', 'done')
        ->orderBy('t.dueDate', 'ASC')
        ->getQuery()
        ->getResult();
    }

    public function findByProject(int $projectId): array
    {
        return $this->createQueryBuilder('t')
        ->andWhere('t.project = :projectId')
        ->setParameter('projectId', $projectId)
        ->orderBy('t.dueDate', 'ASC')
        ->getQuery()
        ->getResult();
    }

    public function findByTagName(string $tagName): array
    {
        return $this->createQueryBuilder('t')
        ->join('t.tags', 'tag')
        ->andWhere('tag.name = :tagName')
        ->setParameter('tagName', $tagName)
        ->getQuery()
        ->getResult();
    }

    public function save(Task $task, bool $flush = false): void
    {
        $this->getEntityManager()->persist($task);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Task $task, bool $flush = false): void
    {
        $this->getEntityManager()->remove($task);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
