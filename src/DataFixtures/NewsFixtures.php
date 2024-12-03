<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class NewsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for($i = 1; $i < 30; $i++){
            $post = new Post();

            $post->setName($faker->words($faker->numberBetween(3,5), true))
                ->setPublished($faker->boolean(90))
                ->setCreatedAt(new \DateTimeImmutable())
                ->setContent($faker->paragraph(6, true))
                ->setSlug($post->getName())
                ->setImage('newsImageDefault.jpg')
            ;
            $manager->persist($post);
        }

        $manager->flush();
    }
}
