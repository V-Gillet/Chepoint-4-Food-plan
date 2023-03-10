<?php

namespace App\Entity;

use App\Repository\MealUserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MealUserRepository::class)]
class MealUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'mealUsers')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'mealUsers')]
    private ?Meal $meal = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Type('boolean')]
    private ?bool $isFavourite = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\DateTime]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMeal(): ?Meal
    {
        return $this->meal;
    }

    public function setMeal(?Meal $meal): self
    {
        $this->meal = $meal;

        return $this;
    }

    public function isIsFavourite(): ?bool
    {
        return $this->isFavourite;
    }

    public function setIsFavourite(?bool $isFavourite): self
    {
        $this->isFavourite = $isFavourite;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
