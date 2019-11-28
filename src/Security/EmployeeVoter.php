<?php

namespace App\Security;

use App\Entity\Account;
use App\Entity\Employee;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class EmployeeVoter extends Voter
{
    const VIEW = 'view';
    const CREATE = 'create';
    const EDIT = 'edit';

    private $security;

    public function __construct(Security $security){
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // If the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW, self::CREATE, self::EDIT])) {
            return false;
        }

        // Only vote on Employee objects inside this voter
        if (!$subject instanceof Employee) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $account = $token->getUser();

        if (!$account instanceof Account) {
            // The user must be logged in; if not, deny access
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                return $this->canView();
            case self::CREATE:
                return $this->canCreate();
            case self::EDIT:
                return $this->canEdit($account, $subject);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView() {
        // Both admins and employees can see any account
        return true;
    }

    private function canCreate() {
        // Only admin can create new employee
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }

    private function canEdit(Account $account, Employee $employee) {

        // Admin can edit any account
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        // Employee can edit only his account
        if ($account->getEmployee()->getId() === $employee->getId()) {
            return true;
        }

        return false;
    }
}
