<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RazdelRepository")
 */
class Razdel
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
     * @ORM\OneToMany(targetEntity="App\Entity\Lection", mappedBy="razdel", orphanRemoval=true)
     */
    private $lections;

    public function __construct()
    {
        $this->lections = new ArrayCollection();
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

    /**
     * @return Collection|Lection[]
     */
    public function getLections(): Collection
    {
        return $this->lections;
    }

    public function addLection(Lection $lection): self
    {
        if (!$this->lections->contains($lection)) {
            $this->lections[] = $lection;
            $lection->setRazdel($this);
        }

        return $this;
    }

    public function removeLection(Lection $lection): self
    {
        if ($this->lections->contains($lection)) {
            $this->lections->removeElement($lection);
            // set the owning side to null (unless already changed)
            if ($lection->getRazdel() === $this) {
                $lection->setRazdel(null);
            }
        }

        return $this;
    }
}
