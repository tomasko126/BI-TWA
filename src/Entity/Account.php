<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccountRepository")
 * @UniqueEntity("username")
 */
class Account implements AdvancedUserInterface
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
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(max="255")
     */
    private $password;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    private $validTo;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Employee", inversedBy="accounts")
     * @var Employee
     */
    private $employee;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getValidTo(): ?\DateTimeInterface
    {
        return $this->validTo;
    }

    public function setValidTo(?\DateTimeInterface $validTo): self
    {
        $this->validTo = $validTo;

        return $this;
    }

    public function getEmployee(): ?Employee
    {
        return $this->employee;
    }

    public function setEmployee(Employee $employee): self
    {
        $this->employee = $employee;

        return $this;
    }

    // We do not enforce using salt while hashing passwords
    public function getSalt() {
        return null;
    }

    // This method should erase sensitive info from the account,
    // but we do not store any sensitive info such as password
    // in plaintext
    public function eraseCredentials() {
        return null;
    }

    // Return user roles
    public function getRoles() {
        $roles = $this->employee->getRoles()->getValues();

        // Logged-in user has this role automatically
        $roleNames = ['ROLE_USER'];

        /* @var Role $role */
        foreach ($roles as $role) {
            if ($role->getIsVisible()) {
                continue;
            }

            $roleNames[] = $role->getTitle();

            if ($role->getTitle() === 'admin') {
                $roleNames[] = 'ROLE_ADMIN';
            }
        }

        return $roleNames;
    }

    public function isAccountNonExpired()
    {
        $date = new \DateTime();

        if ($this->getValidTo() !== null && $this->getValidTo() < $date) {
            return false;
        }

        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return true;
    }
}
