<?php

namespace App\DataFixtures;

use App\Entity\CourseLevel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
class CourseLevelFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $certifications = ['CESS', 'CEB', 'CE1D', 'CE2D'];

        for ($i = 1; $i <= 10; $i++) {
            $courseLevel = new CourseLevel();

            $courseLevel->setName($faker->words($faker->numberBetween(3,5), true))
                        ->setPrerequiste($faker->randomElement($certifications));
            $manager->persist($courseLevel);
        }

        $manager->flush();
    }
}