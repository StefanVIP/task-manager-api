<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
//#[UniqueEntity('slug')]
#[ApiResource(
//    operations: [
//                new Get(normalizationContext: ['groups' => 'task:item']),
//                new GetCollection(normalizationContext: ['groups' => 'task:list'])
//            ],
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

    #[ORM\Column(length: 255)]
    #[Groups(['task:list', 'task:item'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['task:list', 'task:item'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['task:list', 'task:item'])]
    private ?\DateTimeImmutable $createDate;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['task:list', 'task:item'])]
    private ?\DateTimeInterface $completeDate = null;

    #[ORM\Column]
    #[Groups(['task:list', 'task:item'])]
    private ?bool $status = false;

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

    public function setCreateDate(\DateTimeInterface $createDate): static
    {
        $this->createDate = $createDate;

        return $this;
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

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): static
    {
        $this->status = $status;

        return $this;
    }
}
