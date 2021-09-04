<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrdersRepository::class)
 */
class Orders
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="integer")
     */
    private $total;

    /**
     * @ORM\OneToMany(targetEntity=LigneDeCommande::class, mappedBy="commande")
     */
    private $total_ttc;

    public function __construct()
    {
        $this->total_ttc = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return Collection|LigneDeCommande[]
     */
    public function getTotalTtc(): Collection
    {
        return $this->total_ttc;
    }

    public function addTotalTtc(LigneDeCommande $totalTtc): self
    {
        if (!$this->total_ttc->contains($totalTtc)) {
            $this->total_ttc[] = $totalTtc;
            $totalTtc->setCommande($this);
        }

        return $this;
    }

    public function removeTotalTtc(LigneDeCommande $totalTtc): self
    {
        if ($this->total_ttc->removeElement($totalTtc)) {
            // set the owning side to null (unless already changed)
            if ($totalTtc->getCommande() === $this) {
                $totalTtc->setCommande(null);
            }
        }

        return $this;
    }
}
