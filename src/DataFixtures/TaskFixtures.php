<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
         $task = new Task();
         $task->setTitle('Тест');
         $task->setDescription('Тестовое описание');
         $date = new \DateTimeImmutable('2024-05-05');
         $task->setCompleteDate($date);
         $manager->persist($task);

         $manager->flush();
    }
}
