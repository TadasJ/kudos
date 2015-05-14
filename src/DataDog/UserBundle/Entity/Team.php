<?php

namespace DataDog\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DataDog\UserBundle\Entity\User
 *
 * @ORM\Table(name="team")
 * @ORM\Entity(repositoryClass="DataDog\UserBundle\Repository\TeamRepository")
 */
class Team
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
     * @var integer $manager_id
     *
     * @ORM\Column(name="manager_id", type="integer")
     */
    private $manager_id;

    /**
     * @var string #name
     *
     * @Assert\NotBlank(message="Please enter a team name.")
     * @ORM\Column(name="name", type="string", length=128, nullable=false)
     */
    private $name;

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
     * @var User $manager
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @Assert\NotBlank(message="Please select manager.")
     * @ORM\JoinColumn(name="manager_id", referencedColumnName="id")
     */
    private $manager;

    /**
     * @var User[] $users
     * @ORM\ManyToMany(targetEntity="User", inversedBy="teams")
     * @ORM\JoinTable(name="team_to_user")
     */
    private $users;

    public function __construct() {
        $this->users = new ArrayCollection();
    }

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * @return User
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param User $manager
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return User[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param User[] $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    public function addUser(User $user){
        $user->addTeam($this);
        $this->users[] = $user;
    }

    public function __toString(){
        return $this->getName();
    }

}