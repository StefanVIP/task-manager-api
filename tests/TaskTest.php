<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class TaskTest extends ApiTestCase
{
    public function testGetTasks(): void
    {
        $response = static::createClient()->request(method: 'GET', url: '/api/tasks');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["hydra:totalItems" => 1]);
    }

    public function testGetTaskById(): void
    {
        $response = static::createClient()->request(method: 'GET', url: '/api/tasks/1');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => '/api/tasks/1',
            '@type' => 'Task',
            'id' => 1,
            'title' => 'Тест',
            'description' => 'Тестовое описание',
            'completeDate' => '2024-05-05T00:00:00+03:00',
            'status' => false,
        ]);
    }

    public function testPostTask(): void
    {
        $response = static::createClient()->request(method: 'POST', url: '/api/tasks', options: [
            "json" => ['title' => "Test",
                'description' => "Test test",
                'completeDate' => "2025-09-20T13:40:59.500Z"]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["title" => 'Test']);
    }

    public function testPutTask(): void
    {
        $response = static::createClient()->request(method: 'PUT', url: '/api/tasks/1', options: [
            "json" => ['title' => "Test",
                'description' => "Test test",
                'completeDate' => "2025-09-20T13:40:59.500Z"]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => '/api/tasks/1',
            '@type' => 'Task',
            'id' => 1,
            'title' => 'Test',
            'description' => 'Test test',
            'completeDate' => '2025-09-20T13:40:59+03:00',
            'status' => false,
        ]);
    }

    public function testPatchTask(): void
    {
        $response = static::createClient()->request(method: 'PATCH', url: '/api/tasks/1/done', options: [
            'json' => ['status' => true],
            'headers' => ['Content-Type' => 'application/merge-patch+json',
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['status' => true]);
    }

    public function testDeleteTask(): void
    {
        $response = static::createClient()->request(method: 'DELETE', url: '/api/tasks/1');

        $this->assertResponseIsSuccessful();
    }

    public function testTitleValidationPostTask(): void
    {
        $response = static::createClient()->request(method: 'POST', url: '/api/tasks', options: [
            "json" => ['title' => 123,
                'description' => "Test test",
                'completeDate' => "2025-09-20T13:40:59.500Z"]]);

        $this->assertResponseStatusCodeSame(400);
    }

    public function testDescriptionValidationPostTask(): void
    {
        $response = static::createClient()->request(method: 'POST', url: '/api/tasks', options: [
            "json" => ['title' => "Test",
                'description' => null,
                'completeDate' => "2025-09-20T13:40:59.500Z"]]);

        $this->assertResponseStatusCodeSame(400);
    }

    public function testCompleteDateValidationPostTask(): void
    {
        $response = static::createClient()->request(method: 'POST', url: '/api/tasks', options: [
            "json" => ['title' => "Test",
                'description' => "Test test",
                'completeDate' => 123]]);

        $this->assertResponseStatusCodeSame(400);
    }
}
