<?php

namespace App\Controller;

use App\Entity\Employee;
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
            $em = $this->getDoctrine()->getManager();
            $em->persist($employee);
            $em->flush();

            $this->addFlash('notice','Údaje boli úspešne uložené');

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
        $employee = $this->getDoctrine()
            ->getRepository(Employee::class)
            ->find($id);

        if (!$employee) {
            throw $this->createNotFoundException(
                'No employee found for id '. $id
            );
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
        $employee = $this->getDoctrine()
            ->getRepository(Employee::class)
            ->find($id);

        if (!$employee) {
            throw $this->createNotFoundException(
                'No employee found for id ' . $id
            );
        }

        if (!$this->isGranted('edit', $employee)) {
            return $this->render( 'security/403.html.twig', [], (new Response())->setStatusCode( 403 ));
        }

        $form = $this->createForm(EmployeeType::class, $employee);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($employee);
            $em->flush();

            $this->addFlash('notice','Údaje boli úspešne uložené');

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