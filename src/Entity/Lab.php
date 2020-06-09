<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LabRepository")
 */
class Lab
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $videoLink;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LabMaterial", mappedBy="lab", orphanRemoval=true, cascade={"persist"})
     */
    private $labMaterials;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LabResult", mappedBy="lab", orphanRemoval=true)
     */
    private $labResults;

    public function __construct()
    {
        $this->labMaterials = new ArrayCollection();
        $this->labResults = new ArrayCollection();
    }

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

    public function getVideoLink(): ?string
    {
        return $this->videoLink;
    }

    public function setVideoLink(?string $videoLink): self
    {
        $this->videoLink = $videoLink;

        return $this;
    }

    /**
     * @return Collection|LabMaterial[]
     */
    public function getLabMaterials(): Collection
    {
        return $this->labMaterials;
    }

    public function addLabMaterial(LabMaterial $labMaterial): self
    {
        if (!$this->labMaterials->contains($labMaterial)) {
            $this->labMaterials[] = $labMaterial;
            $labMaterial->setLab($this);
        }

        return $this;
    }

    public function removeLabMaterial(LabMaterial $labMaterial): self
    {
        if ($this->labMaterials->contains($labMaterial)) {
            $this->labMaterials->removeElement($labMaterial);
            // set the owning side to null (unless already changed)
            if ($labMaterial->getLab() === $this) {
                $labMaterial->setLab(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LabResult[]
     */
    public function getLabResults(): Collection
    {
        return $this->labResults;
    }

    public function addLabResult(LabResult $labResult): self
    {
        if (!$this->labResults->contains($labResult)) {
            $this->labResults[] = $labResult;
            $labResult->setLab($this);
        }

        return $this;
    }

    public function removeLabResult(LabResult $labResult): self
    {
        if ($this->labResults->contains($labResult)) {
            $this->labResults->removeElement($labResult);
            // set the owning side to null (unless already changed)
            if ($labResult->getLab() === $this) {
                $labResult->setLab(null);
            }
        }

        return $this;
    }
}
