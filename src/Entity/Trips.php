<?php

namespace App\Entity;

use App\Repository\TripsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TripsRepository::class)]
class Trips
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'trips')]
    #[ORM\JoinColumn(nullable: false)]
    private ?route $route = null;

    #[ORM\Column]
    private ?\DateInterval $trip_span = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column]
    private ?int $spots_number = null;

    /**
     * @var Collection<int, Triprequest>
     */
    #[ORM\OneToMany(targetEntity: Triprequest::class, mappedBy: 'trip')]
    private Collection $triprequests;

    public function __construct()
    {
        $this->triprequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoute(): ?route
    {
        return $this->route;
    }

    public function setRoute(?route $route): static
    {
        $this->route = $route;

        return $this;
    }

    public function getTripSpan(): ?\DateInterval
    {
        return $this->trip_span;
    }

    public function setTripSpan(\DateInterval $trip_span): static
    {
        $this->trip_span = $trip_span;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getSpotsNumber(): ?int
    {
        return $this->spots_number;
    }

    public function setSpotsNumber(int $spots_number): static
    {
        $this->spots_number = $spots_number;

        return $this;
    }

    /**
     * @return Collection<int, Triprequest>
     */
    public function getTriprequests(): Collection
    {
        return $this->triprequests;
    }

    public function addTriprequest(Triprequest $triprequest): static
    {
        if (!$this->triprequests->contains($triprequest)) {
            $this->triprequests->add($triprequest);
            $triprequest->setTrip($this);
        }

        return $this;
    }

    public function removeTriprequest(Triprequest $triprequest): static
    {
        if ($this->triprequests->removeElement($triprequest)) {
            // set the owning side to null (unless already changed)
            if ($triprequest->getTrip() === $this) {
                $triprequest->setTrip(null);
            }
        }

        return $this;
    }
}
