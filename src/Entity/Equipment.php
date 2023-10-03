<?php

namespace App\Entity;

use App\Repository\EquipmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipmentRepository::class)]
class Equipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Room::class, inversedBy: 'equipment')]
    private Collection $ofRoom;

    public function __construct()
    {
        $this->ofRoom = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Room>
     */
    public function getOfRoom(): Collection
    {
        return $this->ofRoom;
    }

    public function addOfRoom(Room $ofRoom): static
    {
        if (!$this->ofRoom->contains($ofRoom)) {
            $this->ofRoom->add($ofRoom);
        }

        return $this;
    }

    public function removeOfRoom(Room $ofRoom): static
    {
        $this->ofRoom->removeElement($ofRoom);

        return $this;
    }
}
