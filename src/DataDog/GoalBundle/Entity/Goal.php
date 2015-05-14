<?php

namespace DataDog\GoalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DataDog\GoalBundle\Entity\Goal
 *
 * @ORM\Table(name="goal")
 * @ORM\Entity(repositoryClass="DataDog\GoalBundle\Repository\GoalRepository")
 */
class Goal
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
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=512, nullable=false)
     * @Assert\NotBlank(message="Please enter a title.")
     */
    private $title;

    /**
     * @var string $value
     *
     * @ORM\Column(name="value", type="string", length=128, nullable=true)
     */
    private $value;

    /**
     * @var integer $points_reward
     *
     * @ORM\Column(name="points_reward", type="integer", nullable=false)
     * @Assert\NotBlank(message="Please enter a points reward.")
     */
    private $points_reward;

    /**
     * @var integer $is_active
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false)
     */
    private $is_active;

    /**
     * @var integer $create_at
     *
     * @ORM\Column(name="create_at", type="datetime", nullable=false)
     */
    private $create_at;

    /**
     * @var integer $update_at
     *
     * @ORM\Column(name="update_at", type="datetime", nullable=false)
     */
    private $update_at;

    /**
     * @var Achievement[] $achievements
     *
     * @ORM\OneToMany(targetEntity="Achievement", mappedBy="goal")
     */
    private $achievements;

    public function __toString(){
        return $this->title;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getPointsReward()
    {
        return $this->points_reward;
    }

    /**
     * @param int $points_reward
     */
    public function setPointsReward($points_reward)
    {
        $this->points_reward = $points_reward;
    }

    /**
     * @return int
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * @param int $is_active
     */
    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
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
     * @return int
     */
    public function getUpdateAt()
    {
        return $this->update_at;
    }

    /**
     * @param int $update_at
     */
    public function setUpdateAt($update_at)
    {
        $this->update_at = $update_at;
    }

    /**
     * @return Achievement[]
     */
    public function getAchievements()
    {
        return $this->achievements;
    }

    /**
     * @param Achievement[] $achievements
     */
    public function setAchievement($achievements)
    {
        $this->achievements = $achievements;
    }

    public function addAchievement($achievement){
        $this->achievements[] = $achievement;
    }

    public function achieveCount(){
        return count($this->achievements);
    }

}