<?php

namespace App\DataFixtures;

use App\Entity\CourseCategory;
use App\Entity\Course;
use App\Entity\CourseLevel;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\AsciiSlugger;
class CourseFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $categories = $manager->getRepository(CourseCategory::class)->findAll();
        $level = $manager->getRepository(CourseLevel::class)->findAll();

        for ($i = 1; $i <= 30; $i++) {
            $course = new Course();
            $slugger = new AsciiSlugger();

            $course->setName($faker->words($faker->numberBetween(3,5), true))
                    ->setSmallDescription($faker->words($faker->numberBetween(7,20), true))
                    ->setFullDescription($faker->words($faker->numberBetween(15,75), true))
                    ->setDuration($faker->numberBetween(1, 5) . ' ans' )
                    ->setPrice($faker->randomFloat(2, 120, 1000))
                    ->setIsPublished($faker->boolean(90))
                    ->setSlug($slugger->slug($course->getName()))
                    ->setCreatedAt((\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-3 years', '-2 years'))))
                    ->setUpdatedAt($course->getCreatedAt())
                    ->setCategoryId($categories[$faker->numberBetween(0, count($categories) - 1)])
                    ->setLevelId($level[$faker->numberBetween(0, count($level) - 1)])
                    ->setImageName($i . '.jpg')
                    ->setProgramName('horaire.pdf')
            ;

            $manager->persist($course);
        }

        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            CourseCategoryFixtures::class,
            CourseLevelFixtures::class,
        ];
    }
}