<?php

namespace App\Entities;

class Employee {
    static private $employeeId = 0;

    private $id;
    private $name;
    private $surname;
    private $functions;
    private $email;
    private $phoneNumber;
    private $note;

    /**
     * Employee constructor.
     * @param string $name
     * @param string $surname
     * @param array $functions
     * @param string $email
     * @param string $phoneNumber
     * @param string $note
     */
    public function __construct(string $name, string $surname, array $functions, string $email, string $phoneNumber, string $note = null)
    {
        $this->id = ++static::$employeeId;
        $this->name = $name;
        $this->surname = $surname;
        $this->functions = $functions;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
        $this->note = $note;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return $this->functions;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * @return string
     */
    public function getNote(): ?string
    {
        return $this->note;
    }
}