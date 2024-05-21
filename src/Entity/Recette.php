<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]
#[UniqueEntity('libelle')]
#[Vich\Uploadable]

class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'recettes')]
    private ?Categorie $categorie = null;

    #[ORM\ManyToOne(inversedBy: 'recettes')]
    private ?Difficulte $difficulte = null;

    #[ORM\ManyToOne(inversedBy: 'recettes')]
    private ?Provinces $province = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'recettes')]
    private ?User $auteur = null;

    /**
     * @var Collection<int, Ingredients>
     */
    #[ORM\ManyToMany(targetEntity: Ingredients::class, inversedBy: 'recettes')]
    private Collection $ingredients;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[Vich\UploadableField(mapping: 'recipes', fileNameProperty: 'photos')]
    private ?File $imageFile = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[Vich\UploadableField(mapping: 'recipesVideos', fileNameProperty: 'videos')]
    private ?File $videoFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $videos = null;

    #[ORM\Column]
    private ?bool $isPublic = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $tempsPreparation = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $liked;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->liked = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getDifficulte(): ?Difficulte
    {
        return $this->difficulte;
    }

    public function setDifficulte(?Difficulte $difficulte): static
    {
        $this->difficulte = $difficulte;

        return $this;
    }

    public function getProvince(): ?Provinces
    {
        return $this->province;
    }

    public function setProvince(?Provinces $province): static
    {
        $this->province = $province;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * @return Collection<int, Ingredients>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredients $ingredient): static
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
        }

        return $this;
    }

    public function removeIngredient(Ingredients $ingredient): static
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getVideos(): ?string
    {
        return $this->videos;
    }

    public function setVideos(?string $videos): static
    {
        $this->videos = $videos;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setPublic(bool $isPublic): static
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function getTempsPreparation(): ?\DateTimeInterface
    {
        return $this->tempsPreparation;
    }

    public function setTempsPreparation(\DateTimeInterface $tempsPreparation): static
    {
        $this->tempsPreparation = $tempsPreparation;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getLiked(): Collection
    {
        return $this->liked;
    }

    public function addLiked(User $liked): static
    {
        if (!$this->liked->contains($liked)) {
            $this->liked->add($liked);
        }

        return $this;
    }

    public function removeLiked(User $liked): static
    {
        $this->liked->removeElement($liked);

        return $this;
    }
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setVideoFile(?File $videoFile = null): void
    {
        $this->videoFile = $videoFile;

        if (null !== $videoFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getVideoFile(): ?File
    {
        return $this->videoFile;
    }
}
