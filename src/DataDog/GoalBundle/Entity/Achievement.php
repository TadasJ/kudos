<?php

namespace DataDog\GoalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use DataDog\UserBundle\Entity\User;
use DataDog\GoalBundle\Entity\Goal;

/**
 * DataDog\GoalBundle\Entity\Achievement
 *
 * @ORM\Table(name="achievement")
 * @ORM\Entity(repositoryClass="DataDog\GoalBundle\Repository\AchievementRepository")
 */
class Achievement
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer $goal_id
     *
     * @ORM\Column(name="goal_id", type="integer")
     */
    private $goal_id;

    /**
     * @var integer $user_id
     *
     * @ORM\Column(name="user_id", type="integer")
     */
    private $user_id;

    /**
     * @var integer $manager_id
     *
     * @ORM\Column(name="manager_id", type="integer")
     */
    private $manager_id;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=512, nullable=false)
     */
    private $title;

    /**
     * @var integer $points
     *
     * @ORM\Column(name="points", type="string", nullable=true)
     */
    private $points;

    /**
     * @var integer $create_at
     *
     * @ORM\Column(name="create_at", type="datetime", nullable=false)
     */
    private $create_at;

    public function __toString(){
        return $this->title;
    }

    /**
     * @var Goal $goal
     *
     * @ORM\ManyToOne(targetEntity="Goal")
     * @Assert\NotBlank(message="Please select goal.")
     * @ORM\JoinColumn(name="goal_id", referencedColumnName="id")
     */
    private $goal;

    /**
     * @var \DataDog\UserBundle\User $user
     *
     * @ORM\ManyToOne(targetEntity="\DataDog\UserBundle\User")
     * @Assert\NotBlank(message="Please select user.")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \DataDog\UserBundle\User $manager
     *
     * @ORM\ManyToOne(targetEntity="\DataDog\UserBundle\User")
     * @ORM\JoinColumn(name="manager_id", referencedColumnName="id")
     */
    private $manager;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getGoalId()
    {
        return $this->goal_id;
    }

    /**
     * @param int $goal_id
     */
    public function setGoalId($goal_id)
    {
        $this->goal_id = $goal_id;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return int
     */
    public function getManagerId()
    {
        return $this->manager_id;
    }

    /**
     * @param int $manager_id
     */
    public function setManagerId($manager_id)
    {
        $this->manager_id = $manager_id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * @param int $points
     */
    public function setPoints($points)
    {
        $this->points = $points;
    }

    /**
     * @return int
     */
    public function getCreateAt()
    {
        return $this->create_at;
    }

    /**
     * @param int $create_at
     */
    public function setCreateAt($create_at)
    {
        $this->create_at = $create_at;
    }

    /**
     * @return Goal
     */
    public function getGoal()
    {
        return $this->goal;
    }

    /**
     * @param Goal $goal
     */
    public function setGoal($goal)
    {
        $this->goal = $goal;
    }

    /**
     * @return \DataDog\UserBundle\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \DataDog\UserBundle\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \DataDog\UserBundle\User
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param \DataDog\UserBundle\User $manager
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
    }

}