<?php

namespace App\Entity;

use App\Repository\TrainRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainRepository::class)]
class Train
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\ManyToOne(inversedBy: 'trains')]
	#[ORM\JoinColumn(nullable: false, referencedColumnName: 'name')]
	private ?Destination $destination = null;

	#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
	private ?\DateTimeInterface $arrivalTime = null;

	#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
	private ?\DateTimeInterface $departureTime = null;

	#[ORM\Column(length: 255, nullable: true)]
	private ?string $dock = null;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getDestination(): ?Destination
	{
		return $this->destination;
	}

	public function setDestination(?Destination $destination): static
	{
		$this->destination = $destination;

		return $this;
	}

	public function getArrivalTime(): ?\DateTimeInterface
	{
		return $this->arrivalTime;
	}

	public function setArrivalTime(\DateTimeInterface $arrivalTime): static
	{
		$this->arrivalTime = $arrivalTime;

		return $this;
	}

	public function getDepartureTime(): ?\DateTimeInterface
	{
		return $this->departureTime;
	}

	public function setDepartureTime(?\DateTimeInterface $departureTime): static
	{
		$this->departureTime = $departureTime;

		return $this;
	}

	public function getDock(): ?string
	{
		return $this->dock;
	}

	public function setDock(?string $dock): static
	{
		$this->dock = $dock;

		return $this;
	}
}
