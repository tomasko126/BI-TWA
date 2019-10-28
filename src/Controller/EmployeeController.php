<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Employee;
use App\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController {

    /**
     * @Route("/", name="index")
     */
    public function index() {
        return $this->render( 'index.html.twig');
    }

    /**
     * @Route("/list", name="listaction")
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request) {
        $employeeName = $request->query->get('name');

        $employees = $this->getDoctrine()->getRepository(Employee::class)->findByName($employeeName);
        $roles = [];

        foreach ($employees as &$employee) {
            /** @var Employee $employee */
            $roles[$employee->getId()] = $this->getDoctrine()->getRepository(Role::class)->getRolesForEmployee($employee->getId());
        }

        return $this->render( 'search.html.twig', ['employees' => $employees, 'roles' => $roles]);
    }

    /**
     * @Route("/detail/{id}", name="detailaction")
     * @param int $id
     * @return Response
     */
    public function detailAction(int $id) {
        $employee = $this->getDoctrine()
            ->getRepository(Employee::class)
            ->find($id);

        if (!$employee) {
            throw $this->createNotFoundException(
                'No employee found for id '. $id
            );
        }

        $roles = $this->getDoctrine()->getRepository(Role::class)->getRolesForEmployee($employee->getId());

        return $this->render('detail.html.twig', ['employee' => $employee, 'roles' => $roles]);
    }

    /**
     * @Route("/edit/{id}", name="editaction")
     * @param int $id
     * @return Response
     */
    public function editAction(int $id) {
        $employee = $this->getDoctrine()
            ->getRepository(Employee::class)
            ->find($id);

        if (!$employee) {
            throw $this->createNotFoundException(
                'No employee found for id '. $id
            );
        }

        return $this->render('edit.html.twig', ['employee' => $employee]);
    }

    /**
     * @Route("/accounts/{id}", name="accountsaction")
     * @param int $id
     * @return Response
     */
    public function accountsAction(int $id) {
        $employee = $this->getDoctrine()
            ->getRepository(Employee::class)
            ->find($id);

        if (!$employee) {
            throw $this->createNotFoundException(
                'No employee found for id '. $id
            );
        }

        $accounts = $this->getDoctrine()->getRepository(Account::class)->getAccountsForUser($id);

        return $this->render('accounts.html.twig', ['employee' => $employee, 'accounts' => $accounts]);
    }
}