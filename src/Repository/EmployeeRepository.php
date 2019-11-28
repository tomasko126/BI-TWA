<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function findByName(string $name)
    {
        return $this->createQueryBuilder('e')->where('e.name LIKE :name')
             ->setParameter('name', '%' . $name . '%')
             ->getQuery()->getResult();
    }

    /**
     * @param Employee $employee
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Employee $employee) {
        $this->getEntityManager()->persist($employee);
        $this->getEntityManager()->flush();
    }

    /**
     * @param Employee $employee
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Employee $employee) {
        $this->getEntityManager()->remove($employee);
        $this->getEntityManager()->flush();
    }
}
