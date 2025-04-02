<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $emailUser = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heureDebut = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $heureFin = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $idStationDepart = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $idStationArrivee = null;

    #[ORM\Column]
    private ?string $type = null;

    #[ORM\Column]
    private ?float $distance_km = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmailUser(): ?string
    {
        return $this->emailUser;
    }

    public function setEmailUser(string $emailUser): static
    {
        $this->emailUser = $emailUser;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): static
    {
        $this->Date = $Date;

        return $this;
    }

    public function getHeureDebut(): ?\DateTimeInterface
    {
        return $this->heureDebut;
    }

    public function setHeureDebut(\DateTimeInterface $heureDebut): static
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }

    public function getHeureFin(): ?\DateTimeInterface
    {
        return $this->heureFin;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }
    public function setHeureFin(\DateTimeInterface $heureFin): static
    {
        $this->heureFin = $heureFin;

        return $this;
    }

    public function getIdStationDepart(): ?string
    {
        return $this->idStationDepart;
    }


    public function setIdStationDepart(string $idStationDepart): static
    {
        $this->idStationDepart = $idStationDepart;

        return $this;
    }

    public function getIdStationArrivee(): ?string
    {
        return $this->idStationArrivee;
    }

    public function setIdStationArrivee(string $idStationArrivee): static
    {
        $this->idStationArrivee = $idStationArrivee;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getClearType($type):string
    {
        if ($this->type == "ebike")
        {
            return "Électrique";
        }
        else
        {
            return "Mécanique";
        }
    }

    public function getDistanceKm(): ?float
    {
        return $this->distance_km;
    }

    public function setDistanceKm(float $distance_km): static
    {
        $this->distance_km = $distance_km;

        return $this;
    }
}
