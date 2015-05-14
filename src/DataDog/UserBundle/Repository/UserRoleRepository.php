<?php
namespace DataDog\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRoleRepository extends EntityRepository
{

    const ENTITY_NAME = 'UserBundle:UserRole';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_MANAGER = 'ROLE_MANAGER';
    const ROLE_EMPLOYEE = 'ROLE_EMPLOYEE';

    public function findById($id){
        return $this->getEntityManager()->getRepository(self::ENTITY_NAME)->findOneById($id);
    }

    public function findByValue($value){
        return $this->getEntityManager()->getRepository(self::ENTITY_NAME)->findOneByValue($value);
    }
}