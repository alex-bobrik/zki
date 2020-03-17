<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TestQuestionRepository")
 */
class TestQuestion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Test", inversedBy="testQuestions")
     */
    private $tests;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="testQuestions")
     */
    private $questions;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuestions(): ?Question
    {
        return $this->questions;
    }

    public function setQuestions(?Question $questions): self
    {
        $this->questions = $questions;

        return $this;
    }
}
