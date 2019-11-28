<?php

namespace App\Controller\Rest;

use App\Entity\Employee;
use App\Services\EmployeeService;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\ORMException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EmployeeRestController extends AbstractFOSRestController {

    /** @var EmployeeService */
    private $employeeService;

    /** @var Serializer */
    private $serializer;

    public function __construct(EmployeeService $employeeService) {
        $this->employeeService = $employeeService;
        $this->serializer = $this->serializer = new Serializer([ new ObjectNormalizer() ], [ new JsonEncoder() ]);
    }

    /**
     * @Rest\Get("employee")
     * @return View
     */
    public function getEmployees(): View {
        $employees = $this->employeeService->getEmployees();

        if (empty($employees)) {
            return $this->view($employees, Response::HTTP_NO_CONTENT);
        }

        return $this->view($employees, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("employee/{id}")
     * @param $id
     * @return View
     */
    public function getEmployee($id): View {
        try {
            $employee = $this->employeeService->getEmployee($id);
        } catch (EntityNotFoundException $e) {
            return $this->view('Employee with id ' . $id . ' does not exist!', Response::HTTP_NOT_FOUND);
        }

        return $this->view($employee, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("employee")
     * @param Request $request
     * @return View
     */
    public function createEmployee(Request $request): View {
        // Deserialize the data
        /** @var Employee $employee */
        $employee = $this->serializer->deserialize($request->getContent(), Employee::class, 'json');

        try {
            $result = $this->employeeService->createEmployee($employee);
        } catch (ORMException $e) {
            return $this->view('Server was not able to create new employee!', Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (DBALException $e) {
            return $this->view('Invalid data received', Response::HTTP_BAD_REQUEST);
        }

        return $this->view($result, Response::HTTP_CREATED);
    }

    /**
     * @Rest\Put("employee/{id}")
     * @param int $id
     * @param Request $request
     * @return View
     */
    public function editEmployee(int $id, Request $request): View {
        /** @var Employee $employee */
        $employee = $this->serializer->deserialize($request->getContent(), Employee::class, 'json');

        try {
            $result = $this->employeeService->editEmployee($id, $employee);
        } catch (EntityNotFoundException $e) {
            return $this->view('Employee with id ' . $id . ' does not exist!', Response::HTTP_NOT_FOUND);
        } catch (ORMException $e) {
            return $this->view('Server was not able to update given employee!', Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (DBALException $e) {
            return $this->view('Invalid data received', Response::HTTP_BAD_REQUEST);
        }

        return $this->view($result, Response::HTTP_OK);
    }
}