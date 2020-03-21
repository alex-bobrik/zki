<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TestRepository")
 */
class Test
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TestResult", mappedBy="tests", orphanRemoval=true, cascade={"persist"})
     */
    private $testResults;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TestQuestion", mappedBy="tests", cascade={"persist"}, orphanRemoval=true)
     */
    private $testQuestions;

    public function __construct()
    {
        $this->testResults = new ArrayCollection();
        $this->testQuestions = new ArrayCollection();
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

    /**
     * @return Collection|TestResult[]
     */
    public function getTestResults(): Collection
    {
        return $this->testResults;
    }

    public function addTestResult(TestResult $testResult): self
    {
        if (!$this->testResults->contains($testResult)) {
            $this->testResults[] = $testResult;
            $testResult->setTests($this);
        }

        return $this;
    }

    public function removeTestResult(TestResult $testResult): self
    {
        if ($this->testResults->contains($testResult)) {
            $this->testResults->removeElement($testResult);
            // set the owning side to null (unless already changed)
            if ($testResult->getTests() === $this) {
                $testResult->setTests(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TestQuestion[]
     */
    public function getTestQuestions(): Collection
    {
        return $this->testQuestions;
    }

    public function addTestQuestion(TestQuestion $testQuestion): self
    {
        if (!$this->testQuestions->contains($testQuestion)) {
            $this->testQuestions[] = $testQuestion;
            $testQuestion->setTests($this);
        }

        return $this;
    }

    public function removeTestQuestion(TestQuestion $testQuestion): self
    {
        if ($this->testQuestions->contains($testQuestion)) {
            $this->testQuestions->removeElement($testQuestion);
            // set the owning side to null (unless already changed)
            if ($testQuestion->getTests() === $this) {
                $testQuestion->setTests(null);
            }
        }

        return $this;
    }
}
