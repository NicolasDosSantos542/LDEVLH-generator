<?php

namespace App\Entity;

use App\Repository\GameRepository;
use App\Repository\StepRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $creatorId = null;

    #[ORM\OneToMany(mappedBy: 'gameId', targetEntity: Step::class, orphanRemoval: true)]
    private Collection $steps;

    public function __construct()
    {

        $this->steps = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatorId(): ?int
    {
        return $this->creatorId;
    }

    public function setCreatorId(int $creatorId): static
    {
        $this->creatorId = $creatorId;

        return $this;
    }

    public function getGame(): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "creatorId" => $this->creatorId,
            "steps" => []
        ];
    }

    /**
     * @return Collection<int, Step>
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Step $step): static
    {
        if (!$this->steps->contains($step)) {
            $this->steps->add($step);
            $step->setGameId($this);
        }

        return $this;
    }

    public function removeStep(Step $step): static
    {
        if ($this->steps->removeElement($step)) {
            // set the owning side to null (unless already changed)
            if ($step->getGameId() === $this) {
                $step->setGameId(null);
            }
        }

        return $this;
    }
}
