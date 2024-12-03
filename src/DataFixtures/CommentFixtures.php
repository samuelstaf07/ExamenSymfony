<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Course;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $post = $manager->getRepository(Course::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();
        $faker = Factory::create();

        for($i = 1; $i <= 3; $i++){
            $comment = new Comment();
            $comment->setUser($users[array_rand($users)])
                ->setContent($faker->paragraph(2))
                ->setCourse($post[array_rand($post)])
                ->setRate($faker->numberBetween(1, 5))
                ->setCreatedAt(new \DateTimeImmutable())
                ->setPublished($faker->boolean(90))
            ;
            $manager->persist($comment);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CourseFixtures::class,
            UserFixtures::class,
        ];
    }
}
