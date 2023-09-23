<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class TaskTest extends ApiTestCase
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

    public function testGetTasks(): void
    {
        $json = $this->getJsonWithToken();

        $response = static::createClient()->request(method: 'GET', url: '/api/tasks', options: [
            'auth_bearer' => $json['token']
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["hydra:totalItems" => 1]);
    }

    public function testGetTaskById(): void
    {
        $json = $this->getJsonWithToken();

        $response = static::createClient()->request(method: 'GET', url: '/api/tasks/1', options: [
            'auth_bearer' => $json['token']
        ]);

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
        $json = $this->getJsonWithToken();

        $response = static::createClient()->request(method: 'POST', url: '/api/tasks', options: [
            'auth_bearer' => $json['token'],
            "json" => [
                'title' => "Test",
                'description' => "Test test",
                'completeDate' => "2025-09-20T13:40:59.500Z",
            ]]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(["title" => 'Test']);
    }

    public function testPatchTask(): void
    {
        $json = $this->getJsonWithToken();

        $response = static::createClient()->request(method: 'PATCH', url: '/api/tasks/1', options: [
            'auth_bearer' => $json['token'],
            "json" => ['title' => "Test",
                'description' => "Test test",
                'completeDate' => "2025-09-20T13:40:59.500Z"],
            'headers' => ['Content-Type' => 'application/merge-patch+json']
        ]);

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

    public function testPatchTaskStatus(): void
    {
        $json = $this->getJsonWithToken();

        $response = static::createClient()->request(method: 'PATCH', url: '/api/tasks/1/done', options: [
            'auth_bearer' => $json['token'],
            'json' => ['status' => true],
            'headers' => ['Content-Type' => 'application/merge-patch+json',
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['status' => true]);
    }

    public function testDeleteTask(): void
    {
        $json = $this->getJsonWithToken();

        $response = static::createClient()->request(method: 'DELETE', url: '/api/tasks/1', options: [
            'auth_bearer' => $json['token']
        ]);

        $this->assertResponseIsSuccessful();
    }

    public function testTitleValidationPostTask(): void
    {
        $json = $this->getJsonWithToken();

        $response = static::createClient()->request(method: 'POST', url: '/api/tasks', options: [
            'auth_bearer' => $json['token'],
            "json" => ['title' => 123,
                'description' => "Test test",
                'completeDate' => "2025-09-20T13:40:59.500Z"]]);

        $this->assertResponseStatusCodeSame(400);
    }

    public function testDescriptionValidationPostTask(): void
    {
        $json = $this->getJsonWithToken();

        $response = static::createClient()->request(method: 'POST', url: '/api/tasks', options: [
            'auth_bearer' => $json['token'],
            "json" => ['title' => "Test",
                'description' => null,
                'completeDate' => "2025-09-20T13:40:59.500Z"]]);

        $this->assertResponseStatusCodeSame(400);
    }

    public function testCompleteDateValidationPostTask(): void
    {
        $json = $this->getJsonWithToken();

        $response = static::createClient()->request(method: 'POST', url: '/api/tasks', options: [
            'auth_bearer' => $json['token'],
            "json" => ['title' => "Test",
                'description' => "Test test",
                'completeDate' => 123]]);

        $this->assertResponseStatusCodeSame(400);
    }
}
