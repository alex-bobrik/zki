<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LectionRepository")
 */
class Lection
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isComplete;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Razdel", inversedBy="lections")
     */
    private $razdel;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Materials", mappedBy="lection", cascade={"persist"}, orphanRemoval=true)
     */
    private $materials;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $videoLink;

//    /**
//     * @ORM\OneToMany(targetEntity="App\Entity\Video", mappedBy="lection", cascade={"persist"})
//     */
//    private $videos;

    public function __construct()
    {
        $this->materials = new ArrayCollection();
//        $this->videos = new ArrayCollection();
    }

//    /**
//     * @ORM\Column(type="string", length=255, nullable=true)
//     *
//     * @Assert\NotBlank(message="CHOOSE FILE PLS")
//     */
//    private $lectionFile;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }


    public function getIsComplete(): ?bool
    {
        return $this->isComplete;
    }

    public function setIsComplete(?bool $isComplete): self
    {
        $this->isComplete = $isComplete;

        return $this;
    }

    public function getRazdel(): ?Razdel
    {
        return $this->razdel;
    }

    public function setRazdel(?Razdel $razdel): self
    {
        $this->razdel = $razdel;

        return $this;
    }

    /**
     * @return Collection|Materials[]
     */
    public function getMaterials(): Collection
    {
        return $this->materials;
    }

    public function addMaterial(Materials $material): self
    {
        if (!$this->materials->contains($material)) {
            $this->materials[] = $material;
            $material->setLection($this);
        }

        return $this;
    }

    public function removeMaterial(Materials $material): self
    {
        if ($this->materials->contains($material)) {
            $this->materials->removeElement($material);
            // set the owning side to null (unless already changed)
            if ($material->getLection() === $this) {
                $material->setLection(null);
            }
        }

        return $this;
    }

    public function getVideoLink(): ?string
    {
        return $this->videoLink;
    }

    public function setVideoLink(?string $videoLink): self
    {
        $this->videoLink = $videoLink;

        return $this;
    }

//    /**
//     * @return Collection|Video[]
//     */
//    public function getVideos(): Collection
//    {
//        return $this->videos;
//    }
//
//    public function addVideo(Video $video): self
//    {
//        if (!$this->videos->contains($video)) {
//            $this->videos[] = $video;
//            $video->setLection($this);
//        }
//
//        return $this;
//    }
//
//    public function removeVideo(Video $video): self
//    {
//        if ($this->videos->contains($video)) {
//            $this->videos->removeElement($video);
//            // set the owning side to null (unless already changed)
//            if ($video->getLection() === $this) {
//                $video->setLection(null);
//            }
//        }
//
//        return $this;
//    }

//    public function getLectionFile(): ?string
//    {
//        return $this->lectionFile;
//    }
//
//    public function setLectionFile(?string $lectionFile): self
//    {
//        $this->lectionFile = $lectionFile;
//
//        return $this;
//    }
}
