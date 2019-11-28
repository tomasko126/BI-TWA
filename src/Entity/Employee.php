<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Annotation\ExclusionPolicy;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EmployeeRepository")
 * @ExclusionPolicy("none")
 */
class Employee
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     * @Assert\Url()
     */
    private $web;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    private $room;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", inversedBy="employees")
     * @Serializer\Exclude()
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Account", mappedBy="employee")
     * @Serializer\Exclude()
     */
    private $accounts;

    public function __construct()
    {
        $this->accounts = new ArrayCollection();
        $this->roles = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getWeb(): ?string
    {
        return $this->web;
    }

    public function setWeb(string $web): self
    {
        $this->web = $web;

        return $this;
    }

    public function getRoom(): ?string
    {
        return $this->room;
    }

    public function setRoom(string $room): self
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getRoles(): Collection {
        return $this->roles;
    }

    public function addRole(Role $role): self {
        if ($this->roles->contains($role)) {
            return $this;
        }

        $this->roles[] = $role;

        return $this;
    }

    public function removeRole(Role $role): self {
        if (!$this->roles->contains($role)) {
            return $this;
        }

        $this->roles->remove($role);

        return $this;
    }

    /**
     * @return Collection|Account[]
     */
    public function getAccounts(): Collection {
        return $this->accounts;
    }

    public function addAccount(Account $account): self {
        if ($this->accounts->contains($account)) {
            return $this;
        }

        $this->accounts[] = $account;
        $account->setEmployee($this);

        return $this;
    }

    public function removeAccount(Account $account): self {
        if (!$this->accounts->contains($account)) {
            return $this;
        }

        $this->accounts->remove($account);
        $account->setEmployee(null);

        return $this;
    }
}
