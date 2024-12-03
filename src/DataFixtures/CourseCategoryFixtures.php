<?php
namespace App\DataFixtures;
use App\Entity\CourseCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
class CourseCategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 10; $i++) {
            $courseCategory = new CourseCategory();

            $courseCategory->setName($faker->words($faker->numberBetween(3,5), true))
                ->setDescription($faker->words($faker->numberBetween(20,75), true));
            $manager->persist($courseCategory);
        }

        $manager->flush();
    }
}