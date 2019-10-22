<?php

namespace App\Controller;

use App\Database;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmployeeController extends AbstractController {

    protected $database;

    /**
     * EmployeeController constructor.
     * @param $database
     */
    public function __construct()
    {
        $this->database = new Database();
    }


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

        //var_dump($employeeName);
        //die();
        $employees = $this->database->searchEmployees($employeeName);
        return $this->render( 'search.html.twig', ['employees' => $employees]);
    }

    /**
     * @Route("/detail/{id}", name="detailaction")
     * @param int $id
     * @return Response
     */
    public function detailAction(int $id) {
        $employee = $this->database->getEmployee($id);
        return $this->render('detail.html.twig', ['employee' => $employee]);
    }

    /**
     * @Route("/edit/{id}", name="editaction")
     * @param int $id
     * @return Response
     */
    public function editAction(int $id) {
        $employee = $this->database->getEmployee($id);
        return $this->render('edit.html.twig', ['employee' => $employee]);
    }
}