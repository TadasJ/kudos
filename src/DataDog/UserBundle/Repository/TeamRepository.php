<?php
namespace DataDog\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Proxies\__CG__\DataDog\UserBundle\Entity\UserRole;

class TeamRepository extends EntityRepository
{
    const ENTITY_NAME = 'UserBundle:Team';

    public function findById($id){
        return $this->getEntityManager()->getRepository(self::ENTITY_NAME)->findOneById($id);
    }
}