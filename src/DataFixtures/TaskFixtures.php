<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TaskFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('test1@test.com');
        $password = $this->hasher->hashPassword($user, 'Pass321321');
        $user->setPassword($password);

        $task = new Task();
        $task->setTitle('Тест');
        $task->setDescription('Тестовое описание');
        $date = new \DateTimeImmutable('2024-05-05');
        $task->setCompleteDate($date);
        $task->setUser($user);

        $comment = new Comment();
        $comment->setCommentText('Тестовый комментарий');
        $comment->setTask($task);

        $manager->persist($user);
        $manager->persist($task);
        $manager->persist($comment);

        $manager->flush();
    }
}
