<?php

namespace App\Entity;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ApiResource(
    operations: [
                new Get(),
                new GetCollection(),
                new Post(denormalizationContext: ['groups' => 'task:write']),
                new Put(denormalizationContext: ['groups' => 'task:write']),
                new Patch(uriTemplate: '/tasks/{id}/done', denormalizationContext: ['groups' => 'task:status']),
                new Delete()
            ],
        order: ['createDate' => 'ASC'],
    paginationEnabled: true,
)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['task:list', 'task:item'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(['task:list', 'task:item', 'task:write'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['task:list', 'task:item', 'task:write'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['task:list', 'task:item'])]
    private ?\DateTimeImmutable $createDate;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['task:list', 'task:item', 'task:write'])]
    private ?\DateTimeInterface $completeDate = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Groups(['task:list', 'task:item', 'task:status'])]
    private ?bool $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function __construct()
    {
        $this->createDate = new \DateTimeImmutable();
        $this->status = false;
    }

    public function getCompleteDate(): ?\DateTimeInterface
    {
        return $this->completeDate;
    }

    public function setCompleteDate(\DateTimeInterface $completeDate): static
    {
        $this->completeDate = $completeDate;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status = true): static
    {
        $this->status = $status;

        return $this;
    }
}
