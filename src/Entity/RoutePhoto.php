<?php

namespace App\Entity;

use App\Repository\RoutePhotoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RoutePhotoRepository::class)]
class RoutePhoto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\Column(length: 256)]
    private ?string $photo = null;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    private ?Route $route = null;

    public function setPhoto(?string $file = null): void
    {
        $this->photo = $file;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoute(): ?Route
    {
        return $this->route;
    }

    public function setRoute(?Route $route): static
    {
        $this->route = $route;

        return $this;
    }

   
}
