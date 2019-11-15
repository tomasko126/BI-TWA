<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank()
     */
    private $isVisible;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Employee", mappedBy="roles")
     */
    private $employees;

    public function __construct() {
        $this->employees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): self
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function setEmployee(Employee $employee): self {
        if ($this->employees->contains($employee)) {
            return $this;
        }

        $this->employees[] = $employee;
        $employee->addRole($this);

        return $this;
    }

    public function removeEmployee(Employee $employee): self {
        if (!$this->employees->contains($employee)) {
            return $this;
        }

        $this->employees->remove($employee);
        $employee->removeRole($this);

        return $this;
    }

    public function __toString(): string
    {
        return $this->getTitle();
    }
}
