<?php

namespace App\Entity;

use App\Repository\CustomerPreferenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomerPreferenceRepository::class)]
class CustomerPreference
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $visitFrequency = null;

    #[ORM\OneToOne(inversedBy: 'preferences', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $customer = null;

    #[ORM\ManyToOne]
    private ?User $favoriteProfessional = null;

    /**
     * @var Collection<int, Service>
     */
    #[ORM\ManyToMany(targetEntity: Service::class)]
    private Collection $habitualServices;

    public function __construct()
    {
        $this->habitualServices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVisitFrequency(): ?string
    {
        return $this->visitFrequency;
    }

    public function setVisitFrequency(?string $visitFrequency): static
    {
        $this->visitFrequency = $visitFrequency;
        return $this;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(User $customer): static
    {
        $this->customer = $customer;
        return $this;
    }

    public function getFavoriteProfessional(): ?User
    {
        return $this->favoriteProfessional;
    }

    public function setFavoriteProfessional(?User $favoriteProfessional): static
    {
        $this->favoriteProfessional = $favoriteProfessional;
        return $this;
    }

    /**
     * @return Collection<int, Service>
     */
    public function getHabitualServices(): Collection
    {
        return $this->habitualServices;
    }

    public function addHabitualService(Service $habitualService): static
    {
        if (!$this->habitualServices->contains($habitualService)) {
            $this->habitualServices->add($habitualService);
        }

        return $this;
    }

    public function removeHabitualService(Service $habitualService): static
    {
        $this->habitualServices->removeElement($habitualService);

        return $this;
    }
}
