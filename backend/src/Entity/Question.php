<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?step $step = null;

    #[ORM\Column]
    private ?int $nextStep = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStep(): ?step
    {
        return $this->step;
    }

    public function setStep(?step $step): static
    {
        $this->step = $step;

        return $this;
    }

    public function getNextStep(): ?int
    {
        return $this->nextStep;
    }

    public function setNextStep(int $nextStep): static
    {
        $this->nextStep = $nextStep;

        return $this;
    }
}
