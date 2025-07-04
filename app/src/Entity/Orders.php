<?php

namespace App\Entity;

use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status_payment = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $payment_at = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $price = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Users $users = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    private ?Addresses $addresses = null;

    /**
     * @var Collection<int, Packages>
     */
    #[ORM\OneToMany(targetEntity: Packages::class, mappedBy: 'orders')]
    private Collection $packages;

    /**
     * @var Collection<int, StatusOrders>
     */
    #[ORM\OneToMany(targetEntity: StatusOrders::class, mappedBy: 'orders')]
    private Collection $status_orders;

    public function __construct()
    {
        $this->packages = new ArrayCollection();
        $this->status_orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatusPayment(): ?string
    {
        return $this->status_payment;
    }

    public function setStatusPayment(string $status_payment): static
    {
        $this->status_payment = $status_payment;

        return $this;
    }

    public function getPaymentAt(): ?\DateTimeImmutable
    {
        return $this->payment_at;
    }

    public function setPaymentAt(\DateTimeImmutable $payment_at): static
    {
        $this->payment_at = $payment_at;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getAddresses(): ?Addresses
    {
        return $this->addresses;
    }

    public function setAddresses(?Addresses $addresses): static
    {
        $this->addresses = $addresses;

        return $this;
    }

    /**
     * @return Collection<int, Packages>
     */
    public function getPackages(): Collection
    {
        return $this->packages;
    }

    public function addPackage(Packages $package): static
    {
        if (!$this->packages->contains($package)) {
            $this->packages->add($package);
            $package->setOrders($this);
        }

        return $this;
    }

    public function removePackage(Packages $package): static
    {
        if ($this->packages->removeElement($package)) {
            // set the owning side to null (unless already changed)
            if ($package->getOrders() === $this) {
                $package->setOrders(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, StatusOrders>
     */
    public function getStatusOrders(): Collection
    {
        return $this->status_orders;
    }

    public function addStatusOrder(StatusOrders $statusOrder): static
    {
        if (!$this->status_orders->contains($statusOrder)) {
            $this->status_orders->add($statusOrder);
            $statusOrder->setOrders($this);
        }

        return $this;
    }

    public function removeStatusOrder(StatusOrders $statusOrder): static
    {
        if ($this->status_orders->removeElement($statusOrder)) {
            // set the owning side to null (unless already changed)
            if ($statusOrder->getOrders() === $this) {
                $statusOrder->setOrders(null);
            }
        }

        return $this;
    }
}
