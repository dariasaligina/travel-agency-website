<?php

namespace App\Entity;

use App\Repository\RouteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RouteRepository::class)]
class Route
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 128)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $program = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $additional_info = null;

    /**
     * @var Collection<int, RoutePhoto>
     */
    #[ORM\OneToMany(targetEntity: RoutePhoto::class, mappedBy: 'route')]
    private Collection $photos;

    #[ORM\ManyToOne(inversedBy: 'routes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $departure_city = null;

    #[ORM\ManyToOne(inversedBy: 'routes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $direction = null;

    #[ORM\Column(length: 128)]
    private ?string $duration = null;

    /**
     * @var Collection<int, Trips>
     */
    #[ORM\OneToMany(targetEntity: Trips::class, mappedBy: 'route')]
    private Collection $trips;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->trips = new ArrayCollection();
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

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getProgram(): ?string
    {
        return $this->program;
    }

    public function setProgram(?string $program): static
    {
        $this->program = $program;

        return $this;
    }

    public function getAdditionalInfo(): ?string
    {
        return $this->additional_info;
    }

    public function setAdditionalInfo(?string $additional_info): static
    {
        $this->additional_info = $additional_info;

        return $this;
    }

    /**
     * @return Collection<int, RoutePhoto>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(RoutePhoto $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setRoute($this);
        }

        return $this;
    }

    public function removePhoto(RoutePhoto $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getRoute() === $this) {
                $photo->setRoute(null);
            }
        }

        return $this;
    }

    public function getDepartureCity(): ?City
    {
        return $this->departure_city;
    }

    public function setDepartureCity(?City $departure_city): static
    {
        $this->departure_city = $departure_city;

        return $this;
    }

    public function getDirection(): ?City
    {
        return $this->direction;
    }

    public function setDirection(?City $direction): static
    {
        $this->direction = $direction;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Collection<int, Trips>
     */
    public function getTrips(): Collection
    {
        return $this->trips;
    }

    public function addTrip(Trips $trip): static
    {
        if (!$this->trips->contains($trip)) {
            $this->trips->add($trip);
            $trip->setRoute($this);
        }

        return $this;
    }

    public function removeTrip(Trips $trip): static
    {
        if ($this->trips->removeElement($trip)) {
            // set the owning side to null (unless already changed)
            if ($trip->getRoute() === $this) {
                $trip->setRoute(null);
            }
        }

        return $this;
    }
}
