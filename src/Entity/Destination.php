<?php

namespace App\Entity;

use App\Repository\DestinationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DestinationRepository::class)]
class Destination
{
	public const CATEGORY = ['TGV', 'TER'];

	#[ORM\Id]
	#[ORM\Column(length: 255)]
	private ?string $name = null;

	#[ORM\Column(length: 255)]
	#[Assert\Length(min: 3, max: 3)]
	private ?string $codeStation = null;

	#[ORM\Column(length: 255)]
	#[Assert\Choice(choices: Destination::CATEGORY)]
	private ?string $category = null;

	#[ORM\OneToMany(targetEntity: Train::class, mappedBy: 'destination')]
	private Collection $trains;

	public function __construct()
	{
		$this->trains = new ArrayCollection();
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

	public function getCodeStation(): ?string
	{
		return $this->codeStation;
	}

	public function setCodeStation(string $codeStation): static
	{
		$this->codeStation = $codeStation;

		return $this;
	}

	public function getCategory(): ?string
	{
		return $this->category;
	}

	public function setCategory(string $category): static
	{
		$this->category = $category;

		return $this;
	}

	/**
	 * @return Collection<int, Train>
	 */
	public function getTrains(): Collection
	{
		return $this->trains;
	}

	public function addTrain(Train $train): static
	{
		if (!$this->trains->contains($train)) {
			$this->trains->add($train);
			$train->setDestination($this);
		}

		return $this;
	}

	public function removeTrain(Train $train): static
	{
		if ($this->trains->removeElement($train)) {
			// set the owning side to null (unless already changed)
			if ($train->getDestination() === $this) {
				$train->setDestination(null);
			}
		}

		return $this;
	}
}
