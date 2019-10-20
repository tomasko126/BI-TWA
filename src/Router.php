<?php

namespace App;

class Router
{
    protected $database;
    protected $twig;

    /**
     * Router constructor.
     * @param $twig
     */
    public function __construct($twig)
    {
        $this->database = new Database();
        $this->twig = $twig;
    }

    // Process incoming request
    public function process(string $path = null, string $query = null) {
        $template = null;

        [$placeHolder, $controller, $id] = explode('/', $path);

        // No path has been defined - go to the index page
        if (is_null($controller)) {
            $template = $this->twig->load('index.html.twig');

            echo $template->render([]);
        } else if ($controller === 'search') {
            // Search for an employee
            $template = $this->twig->load('search.html.twig');

            $employees = $this->database->searchEmployees($query);
            echo $template->render(['employees' => $employees]);
        } else if ($controller === 'detail') {
            // Go to the details of employee
            $template = $this->twig->load('detail.html.twig');

            $employee = $this->database->getEmployee($id);
            echo $template->render(['employee' => $employee]);
        }
    }
}