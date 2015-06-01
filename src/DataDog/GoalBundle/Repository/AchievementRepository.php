<?php
namespace DataDog\GoalBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Proxies\__CG__\DataDog\UserBundle\Entity\UserRole;

class AchievementRepository extends EntityRepository
{
    const ENTITY_NAME = 'GoalBundle:Achievement';

    public function findAll(){
        return $this->getEntityManager()
            ->createQuery('SELECT e FROM '.self::ENTITY_NAME.' e ORDER BY e.id DESC')
            ->getResult();
    }
}