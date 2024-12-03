<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class UserFixtures extends Fixture
{
    private object $hasher;

    private array $genders = ['males', 'females'];
    /**
     * UserFixtures constructor.
     * @param UserPasswordHasherInterface $hasher
     */
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 30; $i++) {
            $user = new User();
            $roles = ['ROLE_USER'];
            if ($faker->boolean(10)) {
                $roles[] = 'ROLE_ADMIN';
            }
            $user->setRoles($roles);
            $user->setEmail($faker->email)
                ->setPassword($this->hasher->hashPassword($user, 'password'))
                ->setFirstName($faker->firstName)
                ->setLastName($faker->lastName)
                ->setCreatedAt((\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-3 years', '-2 years'))))
                ->setUpdatedAt(new \DateTimeImmutable())
                ->setLastLogAt(new \DateTimeImmutable())
                ->setDisabled($faker->boolean(10));

            if($faker->boolean(50)){
                $user->setImage('0'. ($i+10) . 'm.jpg');
            }else{
                $user->setImage('0'. ($i+10) . 'f.jpg');
            }

            $manager->persist($user);
        }

        $manager->flush();
    }
}