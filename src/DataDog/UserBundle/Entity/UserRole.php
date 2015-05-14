<?php

namespace DataDog\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\RoleInterface;

/**
 * DataDog\UserBundle\Entity\UserRole
 *
 * @ORM\Table(name="user_role")
 * @ORM\Entity(repositoryClass="DataDog\UserBundle\Repository\UserRoleRepository")
 */
class UserRole implements RoleInterface
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
     * @ORM\Column(name="title", type="string", length=64, nullable=false)
     */
    private $title;

    /**
     * @var string $value
     *
     * @ORM\Column(name="value", type="string", length=64, nullable=false)
     */
    private $value;

    /**
     * @var User[] $users
     *
     * @ORM\OneToMany(targetEntity="User", mappedBy="role")
     */
    private $users;

    public function __construct() {
        $this->users = new ArrayCollection();
    }

    public function getRole(){
        return $this->value;
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
        $user->setRole($this);
        $this->users[] = $user;
    }

    public function __toString(){
        return $this->title;
    }

}