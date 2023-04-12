<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements \App\Domain\UserRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(\App\Domain\Entity\User $user, bool $flush = true): void
    {
        if (!$this->getEntityManager()->contains($user)) {
            $this->getEntityManager()->persist($user);
        }

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function changeStatus(int $id, bool $status)
    {
        $user = $this->find($id);
        $user->setActive($status);
        $this->getEntityManager()->flush();
    }
}
