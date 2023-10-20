<?php

namespace App\Entity;

use App\Repository\HistoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoryRepository::class)]
#[ORM\HasLifecycleCallbacks]
class History
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private  int $id;

    public function getId(): ?int
    {
        return $this->id;
    }
    #[ORM\Column(type: "integer")]
    private int $firstIn;

    #[ORM\Column(type: "integer")]

    private int $secondIn;

    #[ORM\Column(type: "integer", nullable: true)]

    private int $firstOut;

    #[ORM\Column(type: "integer", nullable: true)]

    private int $secondOut;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $updatedAt;

    public function getFirstIn(): int
    {
        return $this->firstIn;
    }

    public function getSecondIn(): int
    {
        return $this->secondIn;
    }

    public function getFirstOut(): int
    {
        return $this->firstOut;
    }

    public function getSecondOut(): int
    {
        return $this->secondOut;
    }

    public function setFirstIn(int $firstIn): void
    {
        $this->firstIn = $firstIn;
    }

    public function setFirstOut(int $firstOut): void
    {
        $this->firstOut = $firstOut;
    }

    public function setSecondIn(int $secondIn): void
    {
        $this->secondIn = $secondIn;
    }

    public function setSecondOut(int $secondOut): void
    {
        $this->secondOut = $secondOut;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTime();
    }

}
