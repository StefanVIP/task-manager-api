<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class CommentTest extends ApiTestCase
{
    public function testGetAllComment(): void
    {
        $response = static::createClient()->request(method: 'GET', url: '/api/comments');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["hydra:totalItems" => 1]);
    }

    public function testPostComment(): void
    {
        $response = static::createClient()->request(method: 'POST', url: '/api/comments', options: [
            "json" => [
                "commentText" => "Test comment",
                "task" => "/api/tasks/1"]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["commentText" => "Test comment"]);
    }

    public function testCommentConnectToTask(): void
    {
        $response = static::createClient()->request(method: 'POST', url: '/api/comments', options: [
            "json" => [
                "commentText" => "Test comment",
                "task" => "/api/tasks/1"]
        ]);

        $response = static::createClient()->request(method: 'GET', url: '/api/tasks/1');


        $this->assertStringContainsString('\/api\/comments\/', $response->getContent());
    }

    public function testDeleteComment(): void
    {
        $response = static::createClient()->request(method: 'DELETE', url: '/api/comments/1');

        $this->assertResponseIsSuccessful();
    }
}
