<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Ya existe una cuenta con este email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_CUSTOMER = 'ROLE_CUSTOMER';
    public const ROLE_EMPLOYEE = 'ROLE_EMPLOYEE';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /** @var list<string> */
    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $surname = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(nullable: true)]
    private ?array $specialties = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'staff')]
    private ?Salon $salon = null;

    /** @var Collection<int, Appointment> */
    #[ORM\OneToMany(targetEntity: Appointment::class, mappedBy: 'customer')]
    private Collection $appointmentsAsCustomer;

    /** @var Collection<int, Appointment> */
    #[ORM\OneToMany(targetEntity: Appointment::class, mappedBy: 'employee')]
    private Collection $appointmentsAsEmployee;

    /** @var Collection<int, EmployeeSchedule> */
    #[ORM\OneToMany(targetEntity: EmployeeSchedule::class, mappedBy: 'employee', cascade: ['persist', 'remove'])]
    private Collection $schedules;

    /** @var Collection<int, Review> */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'customer', orphanRemoval: true)]
    private Collection $reviews;

    #[ORM\OneToOne(mappedBy: 'customer', cascade: ['persist', 'remove'])]
    private ?LoyaltyPoints $loyaltyPoints = null;

    #[ORM\OneToOne(mappedBy: 'customer', cascade: ['persist', 'remove'])]
    private ?CustomerPreference $preferences = null;

    /** @var Collection<int, ProfessionalNote> */
    #[ORM\OneToMany(targetEntity: ProfessionalNote::class, mappedBy: 'customer', orphanRemoval: true)]
    private Collection $professionalNotes;

    /** @var Collection<int, WaitingList> */
    #[ORM\OneToMany(targetEntity: WaitingList::class, mappedBy: 'customer', orphanRemoval: true)]
    private Collection $waitingListItems;

    #[ORM\OneToOne(mappedBy: 'owner', cascade: ['persist', 'remove'])]
    private ?ReferralCode $referralCode = null;

    /** @var Collection<int, BeforeAfterGallery> */
    #[ORM\OneToMany(targetEntity: BeforeAfterGallery::class, mappedBy: 'customer', orphanRemoval: true)]
    private Collection $galleryPhotos;

    /** @var Collection<int, UserAchievement> */
    #[ORM\OneToMany(targetEntity: UserAchievement::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $achievements;

    /** @var Collection<int, VacationRequest> */
    #[ORM\OneToMany(targetEntity: VacationRequest::class, mappedBy: 'employee', orphanRemoval: true)]
    private Collection $vacationRequests;

    #[ORM\Column(nullable: true)]
    private ?float $baseSalary = null;

    #[ORM\Column(nullable: true)]
    private ?float $commissionRate = null; // Percentage (e.g. 0.10 for 10%)

    public function __construct()
    {
        $this->appointmentsAsCustomer = new ArrayCollection();
        $this->appointmentsAsEmployee = new ArrayCollection();
        $this->schedules = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->professionalNotes = new ArrayCollection();
        $this->waitingListItems = new ArrayCollection();
        $this->galleryPhotos = new ArrayCollection();
        $this->achievements = new ArrayCollection();
        $this->vacationRequests = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /** @return list<string> */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    /** @param list<string> $roles */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void {}

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): static
    {
        $this->surname = $surname;
        return $this;
    }

    public function getFullName(): string
    {
        return trim($this->name . ' ' . $this->surname);
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getSpecialties(): ?array
    {
        return $this->specialties;
    }

    public function setSpecialties(?array $specialties): static
    {
        $this->specialties = $specialties;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /** @return Collection<int, Appointment> */
    public function getAppointmentsAsCustomer(): Collection
    {
        return $this->appointmentsAsCustomer;
    }

    /** @return Collection<int, Appointment> */
    public function getAppointmentsAsEmployee(): Collection
    {
        return $this->appointmentsAsEmployee;
    }

    /** @return Collection<int, EmployeeSchedule> */
    public function getSchedules(): Collection
    {
        return $this->schedules;
    }

    public function addSchedule(EmployeeSchedule $schedule): static
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules->add($schedule);
            $schedule->setEmployee($this);
        }
        return $this;
    }

    public function isEmployee(): bool
    {
        return in_array(self::ROLE_EMPLOYEE, $this->roles);
    }

    public function isAdmin(): bool
    {
        return in_array(self::ROLE_ADMIN, $this->roles);
    }

    /** @return Collection<int, Review> */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function getLoyaltyPoints(): ?LoyaltyPoints
    {
        return $this->loyaltyPoints;
    }

    public function setLoyaltyPoints(LoyaltyPoints $loyaltyPoints): static
    {
        if ($loyaltyPoints->getCustomer() !== $this) {
            $loyaltyPoints->setCustomer($this);
        }
        $this->loyaltyPoints = $loyaltyPoints;
        return $this;
    }

    public function getPreferences(): ?CustomerPreference
    {
        return $this->preferences;
    }

    public function setPreferences(CustomerPreference $preferences): static
    {
        if ($preferences->getCustomer() !== $this) {
            $preferences->setCustomer($this);
        }
        $this->preferences = $preferences;
        return $this;
    }

    /** @return Collection<int, ProfessionalNote> */
    public function getProfessionalNotes(): Collection
    {
        return $this->professionalNotes;
    }

    /** @return Collection<int, WaitingList> */
    public function getWaitingListItems(): Collection
    {
        return $this->waitingListItems;
    }

    public function getReferralCode(): ?ReferralCode
    {
        return $this->referralCode;
    }

    public function setReferralCode(ReferralCode $referralCode): static
    {
        // set the owning side of the relation if necessary
        if ($referralCode->getOwner() !== $this) {
            $referralCode->setOwner($this);
        }

        $this->referralCode = $referralCode;

        return $this;
    }

    /** @return Collection<int, BeforeAfterGallery> */
    public function getGalleryPhotos(): Collection
    {
        return $this->galleryPhotos;
    }

    /** @return Collection<int, UserAchievement> */
    public function getAchievements(): Collection
    {
        return $this->achievements;
    }

    /** @return Collection<int, VacationRequest> */
    public function getVacationRequests(): Collection
    {
        return $this->vacationRequests;
    }

    public function getBaseSalary(): ?float
    {
        return $this->baseSalary;
    }

    public function setBaseSalary(?float $baseSalary): static
    {
        $this->baseSalary = $baseSalary;
        return $this;
    }

    public function getCommissionRate(): ?float
    {
        return $this->commissionRate;
    }

    public function setCommissionRate(?float $commissionRate): static
    {
        $this->commissionRate = $commissionRate;
        return $this;
    }
}
