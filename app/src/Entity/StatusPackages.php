<?php

namespace App\Entity;

use App\Repository\StatusPackagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusPackagesRepository::class)]
class StatusPackages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'status_packages')]
    private ?Packages $packages = null;

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

    public function getPackages(): ?Packages
    {
        return $this->packages;
    }

    public function setPackages(?Packages $packages): static
    {
        $this->packages = $packages;

        return $this;
    }
}
