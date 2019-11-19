<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Employee;
use App\Entity\Role;
use App\Form\AccountType;
use App\Form\EmployeeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController {

    /**
     * @Route("/", name="employees")
     * @param Request $request
     * @return Response
     */
    public function employees(Request $request) {
        $employeeName = $request->query->get('name');

        if (empty($employeeName)) {
            $employees = $this->getDoctrine()->getRepository(Employee::class)->findAll();
        } else {
            $employees = $this->getDoctrine()->getRepository(Employee::class)->findByName($employeeName);
        }

        return $this->render( 'employees.html.twig', ['employees' => $employees]);
    }

    /**
     * @Route("/employee/create", name="employeecreate")
     * @param Request $request
     * @return Response
     */
    public function employeeCreate(Request $request) {
        $employee = new Employee();

        $form = $this->createForm(EmployeeType::class, $employee);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($employee);
            $em->flush();

            return $this->redirectToRoute('employees');
        }

        return $this->render('employee_create.html.twig', [
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
        $employee = $this->getDoctrine()
            ->getRepository(Employee::class)
            ->find($id);

        if (!$employee) {
            throw $this->createNotFoundException(
                'No employee found for id '. $id
            );
        }

        return $this->render('employee.html.twig', ['employee' => $employee]);
    }

    /**
     * @Route("/employee/edit/{id}", name="employeeedit", requirements={"id":"\d+"})
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function employeeEdit(int $id, Request $request) {
        $employee = $this->getDoctrine()
            ->getRepository(Employee::class)
            ->find($id);

        if (!$employee) {
            throw $this->createNotFoundException(
                'No employee found for id ' . $id
            );
        }

        $form = $this->createForm(EmployeeType::class, $employee);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($employee);
            $em->flush();

            return $this->redirectToRoute('employee', [
                'id' => $employee->getId(),
            ]);
        }

        return $this->render('employee_edit.html.twig', [
            'employee' => $employee,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/accounts", name="accounts", requirements={"id":"\d+"})
     * @param Request $request
     * @return Response
     */
    public function accounts(Request $request) {
        $employeeId = $request->query->get('employee');

        if (empty($employeeId)) {
            throw $this->createNotFoundException(
                'No employee id has been defined!'
            );
        }

        $employee = $this->getDoctrine()
            ->getRepository(Employee::class)
            ->find($employeeId);

        if (!$employee) {
            throw $this->createNotFoundException(
                'No employee found for id ' . $employeeId
            );
        }

        return $this->render('accounts.html.twig', ['employee' => $employee]);
    }

    /**
     * @Route("/accounts/create", name="accountcreate")
     * @param Request $request
     * @return Response
     */
    public function accountCreate(Request $request) {
        $account = new Account();

        $form = $this->createForm(AccountType::class, $account);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($account);
            $em->flush();

            return $this->redirectToRoute('employees');
        }

        return $this->render('account_create.html.twig', [
            'account' => $account,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/accounts/edit/{id}", name="accountedit", requirements={"id":"\d+"})
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function accountEdit(int $id, Request $request) {
        $account = $this->getDoctrine()->getRepository(Account::class)->find($id);

        if (!$account) {
            throw $this->createNotFoundException(
                'No account found for id ' . $id
            );
        }

        $form = $this->createForm(AccountType::class, $account);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($account);
            $em->flush();
            
            return $this->redirectToRoute('accounts', ['employee' => $id]);
        }

        return $this->render('account_edit.html.twig', [
            'account' => $account,
            'form' => $form->createView()
        ]);
    }
}