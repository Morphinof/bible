<?php

declare(strict_types=1);

namespace Bible\DataFixtures;

use Bible\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Yaml\Yaml;

class UserFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager): void
    {
        $users = Yaml::parseFile(__DIR__.'/fixtures/users.yaml');
        foreach ($users['users'] as $name => $data) {
            $user = $this->createUser($data['email'], $data['password'], $data['roles'] ?? []);
            $manager->persist($user);
        }

        $manager->flush();
    }

    private function createUser(string $email, string $password, array $roles): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
        $user->setRoles($roles);

        return $user;
    }
}
