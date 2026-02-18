<?php

namespace App\Entity;

use App\Repository\AchievementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AchievementRepository::class)]
class Achievement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $icon = null;

    #[ORM\Column]
    private ?int $pointsReward = 0;

    /** @var Collection<int, UserAchievement> */
    #[ORM\OneToMany(targetEntity: UserAchievement::class, mappedBy: 'achievement', orphanRemoval: true)]
    private Collection $userAchievements;

    public function __construct()
    {
        $this->userAchievements = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;
        return $this;
    }

    public function getPointsReward(): ?int
    {
        return $this->pointsReward;
    }

    public function setPointsReward(int $pointsReward): static
    {
        $this->pointsReward = $pointsReward;
        return $this;
    }

    /** @return Collection<int, UserAchievement> */
    public function getUserAchievements(): Collection
    {
        return $this->userAchievements;
    }
}
