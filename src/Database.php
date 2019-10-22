<?php

namespace App;

use App\Entities\Employee;

class Database
{
    protected $employees;

    /**
     * Database constructor.
     */
    public function __construct()
    {
        $this->employees[] = new Employee('Tomáš', 'Taro', ['Student', 'Lecturer'], 'tarotoma@fit.cvut.cz', '721111111', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris molestie, neque eu sodales consectetur, orci nulla pretium purus, vel elementum felis risus non lorem. Vivamus pharetra nisi nec ullamcorper vulputate. In tristique eget enim sodales laoreet. Vivamus id orci congue libero egestas commodo. Fusce quis consectetur nulla. Quisque non mauris in sem vestibulum consequat quis cursus velit. Pellentesque non elit a orci finibus tempus a non justo. In rutrum lacus et lorem molestie, ac luctus magna varius. Etiam elementum nisi non dui vulputate pellentesque.');
        $this->employees[] = new Employee('Test', '1', ['Teacher'], 'example@fit.cvut.cz', '111111111');
        $this->employees[] = new Employee('Test', '2', ['Visitor'], 'example@example.com', '111111111');
    }

    public function getEmployees()
    {
        return $this->employees;
    }

    public function getEmployee(int $id) {
        /** @var Employee $employee */
        foreach ($this->employees as $employee) {
            if ($employee->getId() === $id) {
                return $employee;
            }
        }

        return null;
    }

    public function searchEmployees(string $searchQuery) {
        $searchKeys = null;

        parse_str($searchQuery, $searchKeys);

        $employees = $this->getEmployees();

        if (empty($searchKeys['name'])) {
            return $employees;
        }

        $results = [];

        /** @var Employee $employee */
        foreach ($employees as &$employee) {
            foreach ($searchKeys as &$key) {
                if (strtolower($employee->getName()) === strtolower($key) || strtolower($employee->getSurname()) === strtolower($key)) {
                    $results[] = $employee;
                    break;
                }
            }
        }

        return $results;
    }
}