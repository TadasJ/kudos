<?php
namespace DataDog\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Proxies\__CG__\DataDog\UserBundle\Entity\UserRole;

class UserRepository extends EntityRepository
{
    const ENTITY_NAME = 'UserBundle:User';

    public function findById($id){
        return $this->getEntityManager()->getRepository(self::ENTITY_NAME)->findOneById($id);
    }

    public function findByUsername($username){
        return $this->getEntityManager()->getRepository(self::ENTITY_NAME)->findOneByUsername($username);
    }

    public function findByRoleId($id){
        return $this->getEntityManager()->getRepository(self::ENTITY_NAME)->findBy(['role_id' => $id]);
    }

    public function findAllActiveManagers(){
        $role = $this->getEntityManager()->getRepository('UserBundle:UserRole')->findOneByValue(UserRoleRepository::ROLE_MANAGER);
        return $this->getEntityManager()
            ->createQuery('SELECT e FROM '.self::ENTITY_NAME.' e WHERE e.is_active = 1 AND e.role = :role')
            ->setParameter('role', $role)
            ->getResult();
    }

    public function findAllActiveEmployees(){
        $role = $this->getEntityManager()->getRepository('UserBundle:UserRole')->findOneByValue(UserRoleRepository::ROLE_EMPLOYEE);
        return $this->getEntityManager()
            ->createQuery('SELECT e FROM '.self::ENTITY_NAME.' e WHERE e.is_active = 1 AND e.role = :role')
            ->setParameter('role', $role)
            ->getResult();
    }
}