<?php

namespace App\Repository;

use App\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Course>
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }

    public function findAllPublished(): array
    {
        return $this->createQueryBuilder('course')
                    ->where('course.isPublished = true')
                    ->orderBy('course.createdAt', 'DESC')
                    ->getQuery()
                    ->getResult();
    }

    public function findAllOrderByDateDesc(): array
    {
        return $this->createQueryBuilder('course')
                    ->orderBy('course.created_at', 'DESC')
                    ->getQuery()
                    ->getResult();
    }
}
