<?php

namespace App\Security;

use App\Entity\Account;
use App\Entity\Employee;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class AccountVoter extends Voter
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

        // Only vote on Account objects inside this voter
        if (!$subject instanceof Account) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $account = $token->getUser();

        if (!$account instanceof Account) {
            // the user must be logged in; if not, deny access
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($account, $subject);
            case self::CREATE:
                return $this->canCreate();
            case self::EDIT:
                return $this->canEdit($account, $subject);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Account $account, Account $accountToAccess) {
        // Admin can see any account
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        // Employee can see only his temp accounts,
        // when he is logged in with permanent account
        if ($account->getValidTo() === null && $account->getEmployee()->getId() === $accountToAccess->getEmployee()->getId()) {
            return true;
        }

        return false;
    }

    private function canCreate() {
        // Only admins can create new account
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }

    private function canEdit(Account $account, Account $accountToEdit) {
        // Admin can edit any account
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        // Employee can edit only his temp accounts,
        // when he is logged in with permanent account
        if ($account->getValidTo() === null && $account->getEmployee()->getId() === $accountToEdit->getEmployee()->getId() && $accountToEdit->getValidTo() !== null) {
            return true;
        }

        return false;
    }
}
