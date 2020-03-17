<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TestResultRepository")
 */
class TestResult
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="testResults")
     */
    private $students;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Test", inversedBy="testResults")
     */
    private $tests;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $correctQuestions;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $result;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $currentQuestionNumber;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endDate;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStudents(): ?User
    {
        return $this->students;
    }

    public function setStudents(?User $students): self
    {
        $this->students = $students;

        return $this;
    }

    public function getTests(): ?Test
    {
        return $this->tests;
    }

    public function setTests(?Test $tests): self
    {
        $this->tests = $tests;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getCorrectQuestions(): ?int
    {
        return $this->correctQuestions;
    }

    public function setCorrectQuestions(int $correctQuestions): self
    {
        $this->correctQuestions = $correctQuestions;

        return $this;
    }

    public function getResult(): ?int
    {
        return $this->result;
    }

    public function setResult(?int $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getCurrentQuestionNumber(): ?int
    {
        return $this->currentQuestionNumber;
    }

    public function setCurrentQuestionNumber(?int $currentQuestionNumber): self
    {
        $this->currentQuestionNumber = $currentQuestionNumber;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }
}
