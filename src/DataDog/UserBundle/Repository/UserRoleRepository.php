<?php
namespace DataDog\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRoleRepository extends EntityRepository
{

    const ENTITY_NAME = 'UserBundle:UserRole';
    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    const ROLE_EMPLOYEE = 'employee';

    public function findById($id){
        return $this->getEntityManager()->getRepository(self::ENTITY_NAME)->findOneById($id);
    }

    public function findByValue($value){
        return $this->getEntityManager()->getRepository(self::ENTITY_NAME)->findOneByValue($value);
    }
}