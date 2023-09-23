<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class CommentTest extends ApiTestCase
{
    public function getJsonWithToken(): array
    {
        $response = static::createClient()->request(method: 'POST', url: '/api/login_check', options: [
            "json" => [
                'username' => 'test1@test.com',
                'password' => 'Pass321321',
            ]
        ]);

        return $response->toArray();
    }

    public function testGetComment(): void
    {
        $json = $this->getJsonWithToken();

        $response = static::createClient()->request(method: 'GET', url: '/api/comments/1', options: [
            'auth_bearer' => $json['token']
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["commentText" => "Тестовый комментарий"]);
    }

    public function testPostComment(): void
    {
        $json = $this->getJsonWithToken();

        $response = static::createClient()->request(method: 'POST', url: '/api/comments', options: [
            'auth_bearer' => $json['token'],
            "json" => [
                "commentText" => "Test comment",
                "task" => "/api/tasks/1"]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["commentText" => "Test comment"]);
    }

    public function testCommentConnectToTask(): void
    {
        $json = $this->getJsonWithToken();

        $response = static::createClient()->request(method: 'GET', url: '/api/tasks/1', options: [
            'auth_bearer' => $json['token']
        ]);

        $this->assertStringContainsString('\/api\/comments\/', $response->getContent());
    }

    public function testDeleteComment(): void
    {
        $json = $this->getJsonWithToken();

        $response = static::createClient()->request(method: 'DELETE', url: '/api/comments/1', options: [
            'auth_bearer' => $json['token']
        ]);

        $this->assertResponseIsSuccessful();
    }
}
