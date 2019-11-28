<?php

namespace App\Controller\Rest;

use App\Services\AccountService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use App\Entity\Employee;
use App\Services\EmployeeService;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\ORMException;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AccountRestController extends AbstractFOSRestController {
    /** @var Serializer */
    private $serializer;

    /** @var AccountService */
    private $accountService;


    private $employeeService;

    public function __construct(AccountService $accountService, EmployeeService $employeeService) {
        $this->accountService = $accountService;
        $this->employeeService = $employeeService;
        $this->serializer = $this->serializer = new Serializer([ new ObjectNormalizer() ], [ new JsonEncoder() ]);
    }

    /**
     * @Rest\Get("employee/{employeeId}/accounts")
     * @param int $employeeId
     * @return View
     */
    public function getAccountsForEmployee(int $employeeId): View {
        try {
            $employee = $this->employeeService->getEmployee($employeeId);
        } catch (EntityNotFoundException $e) {
            return $this->view('Employee with id ' . $employeeId . ' does not exist!', Response::HTTP_NOT_FOUND);
        }

        $accounts = $employee->getAccounts();

        return $this->view($accounts, Response::HTTP_OK);
    }
}