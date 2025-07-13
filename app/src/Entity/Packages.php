<?php

namespace App\Entity;

use App\Repository\PackagesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PackagesRepository::class)]
class Packages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $poids_kg = null;

    #[ORM\Column]
    private ?string $dimensions_cm = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $reception_at = null;

    #[ORM\Column(length: 50)]
    private ?string $reference = null;

    #[ORM\ManyToOne(inversedBy: 'packages')]
    private ?Orders $orders = null;

    /**
     * @var Collection<int, StatusPackages>
     */
    #[ORM\OneToMany(targetEntity: StatusPackages::class, mappedBy: 'packages')]
    private Collection $status_packages;

    public function __construct()
    {
        $this->status_packages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoidsKg(): ?int
    {
        return $this->poids_kg;
    }

    public function setPoidsKg(int $poids_kg): static
    {
        $this->poids_kg = $poids_kg;

        return $this;
    }

    public function getDimensionsCm(): ?string
    {
        return $this->dimensions_cm;
    }

    public function setDimensionsCm(?string $dimensions_cm): static
    {
        $this->dimensions_cm = $dimensions_cm;

        return $this;
    }

    public function getReceptionAt(): ?\DateTimeImmutable
    {
        return $this->reception_at;
    }

    public function setReceptionAt(\DateTimeImmutable $reception_at): static
    {
        $this->reception_at = $reception_at;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getOrders(): ?Orders
    {
        return $this->orders;
    }

    public function setOrders(?Orders $orders): static
    {
        $this->orders = $orders;

        return $this;
    }

    /**
     * @return Collection<int, StatusPackages>
     */
    public function getStatusPackages(): Collection
    {
        return $this->status_packages;
    }

    public function addStatusPackage(StatusPackages $statusPackage): static
    {
        if (!$this->status_packages->contains($statusPackage)) {
            $this->status_packages->add($statusPackage);
            $statusPackage->setPackages($this);
        }

        return $this;
    }

    public function removeStatusPackage(StatusPackages $statusPackage): static
    {
        if ($this->status_packages->removeElement($statusPackage)) {
            // set the owning side to null (unless already changed)
            if ($statusPackage->getPackages() === $this) {
                $statusPackage->setPackages(null);
            }
        }

        return $this;
    }

    public function getPrice(): int
    {
        // 4,99€ jusqu'à 3kg, puis +2€ par kg supplémentaire
        $base = 499; // 4,99€ en centimes
        $poids = $this->getPoidsKg() ?? 0;
        if ($poids > 3) {
            $base += (int) ceil($poids - 3) * 200; // 2€ (200 centimes) par kg supplémentaire
        }
        return $base;
    }


}
