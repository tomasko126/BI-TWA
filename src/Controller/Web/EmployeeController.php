<?php

namespace App\Controller\Web;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Services\EmployeeService;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController {

    /** @var EmployeeService */
    private $employeeService;

    public function __construct(EmployeeService $employeeService) {
        $this->employeeService = $employeeService;
    }

    /**
     * @Route("/", name="employees")
     * @param Request $request
     * @return Response
     */
    public function employees(Request $request) {
        $employeeName = $request->query->get('name');

        if (empty($employeeName)) {
            $employees = $this->employeeService->getEmployees();
        } else {
            $employees = $this->employeeService->getEmployeesByName($employeeName);
        }

        return $this->render( 'employee/employees.html.twig', ['employees' => $employees]);
    }

    /**
     * @Route("/employee/create", name="employeecreate")
     * @param Request $request
     * @return Response
     */
    public function employeeCreate(Request $request) {
        $employee = new Employee();

        if (!$this->isGranted('create', $employee)) {
            return $this->render( 'security/403.html.twig', [], (new Response())->setStatusCode( 403 ));
        }

        $form = $this->createForm(EmployeeType::class, $employee);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            try {
                $this->employeeService->createEmployee($formData);

                $this->addFlash('notice', 'Údaje boli úspešne uložené');
            } catch (OptimisticLockException | ORMException | DBALException $e) {
                $this->addFlash('error', 'Údaje neboli uložené!');
            }

            return $this->redirectToRoute('employees');
        }

        return $this->render('employee/employee_create.html.twig', [
            'form' => $form->createView(),
            'employee' => $employee
        ]);
    }

    /**
     * @Route("/employee/{id}", name="employee", requirements={"id":"\d+"})
     * @param int $id
     * @return Response
     */
    public function employee(int $id) {
        try {
            $employee = $this->employeeService->getEmployee($id);
        } catch (EntityNotFoundException $e) {
            throw $this->createNotFoundException('No employee found for id ' . $id);
        }

        if (!$this->isGranted('view', $employee)) {
            return $this->render( 'security/403.html.twig', [], (new Response())->setStatusCode( 403 ));
        }

        return $this->render('employee/employee.html.twig', ['employee' => $employee]);
    }

    /**
     * @Route("/employee/edit/{id}", name="employeeedit", requirements={"id":"\d+"})
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function employeeEdit(int $id, Request $request) {
        try {
            $employee = $this->employeeService->getEmployee($id);
        } catch (EntityNotFoundException $e) {
            throw $this->createNotFoundException('No employee found for id ' . $id);
        }

        if (!$this->isGranted('edit', $employee)) {
            return $this->render( 'security/403.html.twig', [], (new Response())->setStatusCode( 403 ));
        }

        $form = $this->createForm(EmployeeType::class, $employee);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            try {
                $this->employeeService->editEmployee($id, $formData);

                $this->addFlash('notice','Údaje boli úspešne uložené.');
            } catch (EntityNotFoundException | OptimisticLockException | ORMException | DBALException $e) {
                $this->addFlash('error', 'Údaje neboli uložené!');
            }

            return $this->redirectToRoute('employee', [
                'id' => $employee->getId(),
            ]);
        }

        return $this->render('employee/employee_edit.html.twig', [
            'employee' => $employee,
            'form' => $form->createView()
        ]);
    }
}