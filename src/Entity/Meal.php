<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MealRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MealRepository::class)]
#[Vich\Uploadable]
class Meal
{
    public const MEAL_TYPE = ['Petit-déjeuner', 'Déjeuner', 'En-cas', 'Diner'];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(max: 255)]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Type('string')]
    private ?string $description = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Type('string')]
    #[Assert\Length(max: 100)]
    private ?string $origin = null;

    #[ORM\Column]
    #[Assert\Type('integer')]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?int $lipid = null;

    #[ORM\Column]
    #[Assert\Type('integer')]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?int $carb = null;

    #[ORM\Column]
    #[Assert\Type('integer')]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?int $protein = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Type('integer')]
    #[Assert\Positive]
    private ?int $calories = null;

    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Type('string')]
    #[Assert\Length(max: 100)]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Type('boolean')]
    private ?bool $isRecipe = null;

    #[ORM\OneToMany(mappedBy: 'meal', targetEntity: MealUser::class, cascade: ['remove'])]
    private Collection $mealUsers;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTimeInterface $date = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Type('boolean')]
    private ?bool $isFavourite = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Type('string')]
    #[Assert\Length(max: 255)]
    private ?string $poster = null;

    #[Vich\UploadableField(mapping: 'poster_file', fileNameProperty: 'poster')]
    #[Assert\File(
        maxSize: '5M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
    )]
    private ?File $posterFile = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DatetimeInterface $updatedAt = null;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

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

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function setPosterFile(File $image = null): self
    {
        $this->posterFile = $image;

        if ($image) {
            $this->updatedAt = new DateTime('now');
        }

        return $this;
    }


    public function getPosterFile(): ?File
    {
        return $this->posterFile;
    }
}
