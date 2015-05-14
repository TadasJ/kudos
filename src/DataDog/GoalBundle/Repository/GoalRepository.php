<?php
namespace DataDog\GoalBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Proxies\__CG__\DataDog\UserBundle\Entity\UserRole;

class GoalRepository extends EntityRepository
{
    const ENTITY_NAME = 'GoalBundle:Goal';

    public function findAllActive(){
        return $this->getEntityManager()
            ->createQuery('SELECT e FROM '.self::ENTITY_NAME.' e WHERE e.is_active = 1 ORDER BY e.points_reward DESC')
            ->getResult();
    }

    public function findAll(){
        return $this->getEntityManager()
            ->createQuery('SELECT e FROM '.self::ENTITY_NAME.' e ORDER BY e.is_active DESC, e.points_reward DESC')
            ->getResult();
    }
}