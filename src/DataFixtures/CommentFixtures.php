<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $comment = new Comment();
        $comment->setCommentText('Тестовый комментарий');

        $manager->persist($comment);

        $manager->flush();
    }
}
