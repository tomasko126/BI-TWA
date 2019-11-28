<?php

namespace App\Services;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class AccountService {
    private $repository;

    public function __construct(AccountRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @param $id
     * @return Account|null
     * @throws EntityNotFoundException
     */
    public function getAccount(int $id) {
        $account = $this->repository->find($id);

        if (!$account) {
            throw new EntityNotFoundException('Account with id ' . $id . ' does not exist!');
        }

        return $account;
    }

    /**
     * @param Account $account
     * @return Account|void
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function createAccount(Account $account) {
        $account = $this->repository->save($account);

        return $account;
    }

    /**
     * @param int $id
     * @param Account $accountToSave
     * @throws EntityNotFoundException
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function editAccount(int $id, Account $accountToSave) {
        $this->getAccount($id);

        $account = $this->repository->save($accountToSave);

        return $account;
    }
}