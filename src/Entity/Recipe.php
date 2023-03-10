<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity('name')]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank]
    #[Assert\Length(min: '2', max: '50')]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive]
    #[Assert\LessThan(1441)]
    private ?int $minute = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive]
    #[Assert\LessThan(51)]
    private ?int $numberOfPer = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive]
    #[Assert\LessThan(6)]
    private ?int $difficulty = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private ?string $step_description = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive]
    #[Assert\LessThan(1001)]
    private ?float $price = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isFavorite = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\ManyToMany(targetEntity: Ingredient::class, inversedBy: 'recipes')]
    private Collection $ingredientsList;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->ingredientsList = new ArrayCollection();
        $this->createAt = new \DateTimeImmutable();
        $this->updateAt = new \DateTimeImmutable();
    }

    #[ORM\PrePersist]
    public function setUpdatedAtValue()
    {
        $this->updateAt = new \DateTimeImmutable();
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

    public function getMinute(): ?string
    {
        return $this->minute;
    }

    public function setMinute(mixed $minute): self
    {
        $this->minute = $minute;

        return $this;
    }

    public function getNumberOfPer(): ?int
    {
        return $this->numberOfPer;
    }

    public function setNumberOfPer(mixed $numberOfPer): self
    {
        $this->numberOfPer = $numberOfPer;

        return $this;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(mixed $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getStepDescription(): ?string
    {
        return $this->step_description;
    }

    public function setStepDescription(string $step_description): self
    {
        $this->step_description = $step_description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(mixed $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getIsFavorite(): ?bool
    {
        return $this->isFavorite;
    }

    public function setIsFavorite(bool $favorite): self
    {
        $this->isFavorite = $favorite;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getIngredientsList(): Collection
    {
        return $this->ingredientsList;
    }

    public function addIngredientsList(Ingredient $ingredientsList): self
    {
        if (!$this->ingredientsList->contains($ingredientsList)) {
            $this->ingredientsList->add($ingredientsList);
        }

        return $this;
    }

    public function removeIngredientsList(Ingredient $ingredientsList): self
    {
        $this->ingredientsList->removeElement($ingredientsList);

        return $this;
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
}
