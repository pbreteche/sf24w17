<?php

namespace App\Entity;

use App\Enum\PostState;
use App\Repository\PostRepository;
use App\Validator\NotDeleted;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[NotDeleted]
#[ORM\HasLifecycleCallbacks]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 80)]
    private ?string $title = null;

    #[ORM\Column(nullable: true)]
    #[Assert\GreaterThan('now', groups: ['create'])]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(min: 50)]
    private ?string $body = null;

    #[ORM\Column(length: 255, enumType: PostState::class)]
    private PostState $state = PostState::Draft;

    #[ORM\ManyToOne]
    private ?Category $filedIn = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $authoredBy = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

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

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(?string $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function getState(): PostState
    {
        return $this->state;
    }

    public function setState(PostState $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getFiledIn(): ?Category
    {
        return $this->filedIn;
    }

    public function setFiledIn(?Category $filedIn): static
    {
        $this->filedIn = $filedIn;

        return $this;
    }

    #[Assert\IsTrue(message: 'Le titre doit comporter  une longueur paire')]
    public function isTitleLengthEven(): bool
    {
        return !(strlen($this->title) % 2);
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context, mixed $payload): void
    {
        if (
            PostState::Published == $this->state
            && empty($this->body)
        ) {
            $context->buildViolation('Body should not be empty for published post')
                ->atPath('body')
                ->addViolation();
        }
    }

    public function getAuthoredBy(): ?User
    {
        return $this->authoredBy;
    }

    public function setAuthoredBy(?User $authoredBy): static
    {
        $this->authoredBy = $authoredBy;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): static
    {
        $this->updatedAt = new \DateTimeImmutable();

        return $this;
    }
}
