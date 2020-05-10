<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullName;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Groups", inversedBy="users")
     */
    private $groups;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TestResult", mappedBy="students", orphanRemoval=true)
     */
    private $testResults;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LabResult", mappedBy="user", orphanRemoval=true)
     */
    private $labResults;

    public function __construct()
    {
        $this->testResults = new ArrayCollection();
        $this->labResults = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getGroups(): ?Groups
    {
        return $this->groups;
    }

    public function setGroups(?Groups $groups): self
    {
        $this->groups = $groups;

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
            $testResult->setStudents($this);
        }

        return $this;
    }

    public function removeTestResult(TestResult $testResult): self
    {
        if ($this->testResults->contains($testResult)) {
            $this->testResults->removeElement($testResult);
            // set the owning side to null (unless already changed)
            if ($testResult->getStudents() === $this) {
                $testResult->setStudents(null);
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
            $labResult->setUser($this);
        }

        return $this;
    }

    public function removeLabResult(LabResult $labResult): self
    {
        if ($this->labResults->contains($labResult)) {
            $this->labResults->removeElement($labResult);
            // set the owning side to null (unless already changed)
            if ($labResult->getUser() === $this) {
                $labResult->setUser(null);
            }
        }

        return $this;
    }
}
