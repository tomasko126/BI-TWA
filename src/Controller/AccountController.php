<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Employee;
use App\Form\AccountType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
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

        // Get the employee's any account in order to met the security condition
        $account = null;
        if (empty($employee->getAccounts()->getValues())) {
            $account = new Account();
            $account->setEmployee($employee);
        } else {
            $account = $employee->getAccounts()->getValues()[0];
        }

        if (!$this->isGranted('view', $account)) {
            return $this->render( 'security/403.html.twig', [], (new Response())->setStatusCode( 403 ));
        }

        // If user is not admin, he can't see his permanent accounts
        $isAdmin = in_array('ROLE_ADMIN', $this->getUser()->getRoles());
        $accounts = $employee->getAccounts()->getValues();

        if (!$isAdmin) {
            $i = 0;
            foreach ($accounts as &$account) {
                if ($account->getValidTo() === null) {
                    unset($accounts[$i]);
                }
                $i++;
            }
        }

        return $this->render('account/accounts.html.twig', ['accounts' => $accounts]);
    }

    /**
     * @Route("/accounts/create", name="accountcreate")
     * @param Request $request
     * @return Response
     */
    public function accountCreate(Request $request) {
        $account = new Account();

        if (!$this->isGranted('create', $account)) {
            return $this->render( 'security/403.html.twig', [], (new Response())->setStatusCode( 403 ));
        }

        $form = $this->createForm(AccountType::class, $account);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Hash password
            $account = $form->getData();
            $account->setPassword(password_hash($form["password"]->getData(), PASSWORD_BCRYPT));

            $em = $this->getDoctrine()->getManager();
            $em->persist($account);
            $em->flush();

            $this->addFlash('notice','Údaje boli úspešne uložené');

            return $this->redirectToRoute('employees');
        }

        return $this->render('account/account_create.html.twig', [
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

        if (!$this->isGranted('edit', $account)) {
            return $this->render( 'security/403.html.twig', [], (new Response())->setStatusCode( 403 ));
        }

        $form = $this->createForm(AccountType::class, $account);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash password
            $account = $form->getData();
            $account->setPassword(password_hash($form["password"]->getData(), PASSWORD_BCRYPT));

            $em = $this->getDoctrine()->getManager();
            $em->persist($account);
            $em->flush();

            $this->addFlash('notice','Údaje boli úspešne uložené');

            return $this->redirectToRoute('accounts', ['employee' => $id]);
        }

        return $this->render('account/account_edit.html.twig', [
            'account' => $account,
            'form' => $form->createView()
        ]);
    }
}
