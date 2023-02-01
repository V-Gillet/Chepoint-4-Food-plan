<?php

namespace App\Entity;

use App\Repository\MealRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MealRepository::class)]
class Meal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $origin = null;

    #[ORM\Column]
    private ?int $lipid = null;

    #[ORM\Column]
    private ?int $carb = null;

    #[ORM\Column]
    private ?int $protein = null;

    #[ORM\Column(nullable: true)]
    private ?int $calories = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isRecipe = null;

    #[ORM\OneToMany(mappedBy: 'meal', targetEntity: MealUser::class)]
    private Collection $mealUsers;

    public function __construct()
    {
        $this->mealUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getOrigin(): ?string
    {
        return $this->origin;
    }

    public function setOrigin(?string $origin): self
    {
        $this->origin = $origin;

        return $this;
    }

    public function getLipid(): ?int
    {
        return $this->lipid;
    }

    public function setLipid(int $lipid): self
    {
        $this->lipid = $lipid;

        return $this;
    }

    public function getCarb(): ?int
    {
        return $this->carb;
    }

    public function setCarb(int $carb): self
    {
        $this->carb = $carb;

        return $this;
    }

    public function getProtein(): ?int
    {
        return $this->protein;
    }

    public function setProtein(int $protein): self
    {
        $this->protein = $protein;

        return $this;
    }

    public function getCalories(): ?int
    {
        return $this->calories;
    }

    public function setCalories(?int $calories): self
    {
        $this->calories = $calories;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isIsRecipe(): ?bool
    {
        return $this->isRecipe;
    }

    public function setIsRecipe(?bool $isRecipe): self
    {
        $this->isRecipe = $isRecipe;

        return $this;
    }

    /**
     * @return Collection<int, MealUser>
     */
    public function getMealUsers(): Collection
    {
        return $this->mealUsers;
    }

    public function addMealUser(MealUser $mealUser): self
    {
        if (!$this->mealUsers->contains($mealUser)) {
            $this->mealUsers->add($mealUser);
            $mealUser->setMeal($this);
        }

        return $this;
    }

    public function removeMealUser(MealUser $mealUser): self
    {
        if ($this->mealUsers->removeElement($mealUser)) {
            // set the owning side to null (unless already changed)
            if ($mealUser->getMeal() === $this) {
                $mealUser->setMeal(null);
            }
        }

        return $this;
    }
}
