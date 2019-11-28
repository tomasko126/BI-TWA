<?php

namespace App\Services;
use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class EmployeeService {

    private $repository;

    public function __construct(EmployeeRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @return Employee[]
     */
    public function getEmployees() {
        return $this->repository->findAll();
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getEmployeesByName(string $name) {
        return $this->repository->findByName($name);
    }

    /**
     * @param int $id
     * @return Employee|null
     * @throws EntityNotFoundException
     */
    public function getEmployee(int $id) {
        $employee = $this->repository->find($id);

        if (!$employee) {
            throw new EntityNotFoundException('Employee with id ' . $id . ' does not exist!');
        }

        return $employee;
    }

    /**
     * @param Employee $employee
     * @return Employee
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createEmployee(Employee $employee) {
        $employee = $this->repository->save($employee);

        return $employee;
    }

    /**
     * @param int $id
     * @param Employee $employeeToSave
     * @return Employee
     * @throws EntityNotFoundException
     * @throws ORMException
     */
    public function editEmployee(int $id, Employee $employeeToSave) {
        // If an employee with this id does not exist,
        // an exception will be thrown
        $this->getEmployee($id);

        $employee = $this->repository->save($employeeToSave);

        return $employee;
    }
}