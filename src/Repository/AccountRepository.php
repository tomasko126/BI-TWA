<?php

namespace App\Repository;

use App\Entity\Account;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    /**
     * @param Account $account
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Account $account) {
        $this->getEntityManager()->persist($account);
        $this->getEntityManager()->flush();
    }

    /**
     * @param Account $account
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Account $account) {
        $this->getEntityManager()->persist($account);
        $this->getEntityManager()->flush();
    }
}
