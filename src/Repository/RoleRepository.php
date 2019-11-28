<?php

namespace App\Repository;

use App\Entity\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @method Role|null find($id, $lockMode = null, $lockVersion = null)
 * @method Role|null findOneBy(array $criteria, array $orderBy = null)
 * @method Role[]    findAll()
 * @method Role[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Role::class);
    }

    /**
     * @param Role $role
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Role $role) {
        $this->getEntityManager()->persist($role);
        $this->getEntityManager()->flush();
    }

    /**
     * @param Role $role
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Role $role) {
        $this->getEntityManager()->persist($role);
        $this->getEntityManager()->flush();
    }
}
