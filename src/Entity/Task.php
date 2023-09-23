<?php

namespace App\Entity;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => 'task:item'],
            securityPostDenormalize: "is_granted('ROLE_USER') and object.user == user",
            securityPostDenormalizeMessage: 'Sorry, but you are not the task owner.'
        ),
        new GetCollection(
            paginationMaximumItemsPerPage: 10,
            normalizationContext: ['groups' => 'task:list']
        ),
        new Post(
            denormalizationContext: ['groups' => 'task:write'],
            securityPostDenormalize: "is_granted('ROLE_USER')",
            securityPostDenormalizeMessage: 'Sorry, but you are not the actual task owner.'
        ),
        new Patch(
            uriTemplate: '/tasks/{id}',
            denormalizationContext: ['groups' => 'task:write'],
            security: "is_granted('ROLE_USER') and object.user == user",
            securityPostDenormalizeMessage: 'Sorry, but you are not the actual task owner.'
        ),
        new Patch(
            uriTemplate: '/tasks/{id}/done',
            denormalizationContext: ['groups' => 'task:status'],
            security: "is_granted('ROLE_USER') and object.user == user",
            securityPostDenormalizeMessage: 'Sorry, but you are not the actual task owner.'
        ),
        new Delete(securityPostDenormalize: "is_granted('ROLE_USER') and object.user == user",
            securityPostDenormalizeMessage: 'Sorry, but you are not the actual task owner.'
        )
    ],
    order: ['createDate' => 'ASC'],
    paginationEnabled: true,
    security: "is_granted('ROLE_USER')"
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

    #[ORM\OneToMany(mappedBy: 'task', targetEntity: Comment::class, cascade: ['persist', 'remove'])]
    private Collection $comments;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    public ?User $user = null;

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
        $this->comments = new ArrayCollection();
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

    #[Groups(['task:list', 'task:item'])]
    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setTask($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {

            if ($comment->getTask() === $this) {
                $comment->setTask(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
