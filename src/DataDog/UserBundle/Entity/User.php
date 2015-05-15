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
 * @UniqueEntity(fields="username", message="Username already taken")
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="DataDog\UserBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface, \Serializable
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
     * @var integer $role_id
     *
     * @ORM\Column(name="role_id", type="integer")
     */
    private $role_id;

    /**
     * @var string $username
     *
     * @Assert\NotBlank(message="Please enter a Username.")
     * @ORM\Column(name="username", type="string", length=128, nullable=false)
     */
    private $username;

    /**
     * @var string $password_hash
     *
     * @ORM\Column(name="password_hash", type="string", length=255, nullable=false)
     */
    private $password_hash;

    /**
     * @var string $first_name
     *
     * @ORM\Column(name="first_name", type="string", length=64, nullable=false)
     */
    private $first_name;

    /**
     * @var string $last_name
     *
     * @ORM\Column(name="last_name", type="string", length=64, nullable=false)
     */
    private $last_name;

    /**
     * @var integer $total_points
     *
     * @ORM\Column(name="total_points", type="integer")
     */
    private $total_points;

    /**
     * @var integer $is_active
     *
     * @ORM\Column(name="is_active", type="boolean")
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
     * @var integer $login_at
     *
     * @ORM\Column(name="login_at", type="datetime", nullable=false)
     */
    private $login_at;

    /**
     * @var UserRole $role
     *
     * @ORM\ManyToOne(targetEntity="UserRole")
     * @Assert\NotBlank(message="Please select role.")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     */
    private $role;

    /**
     * @var Team[] $managedTeams
     *
     * @ORM\OneToMany(targetEntity="Team", mappedBy="manager")
     */
    private $managedTeams;

    /**
     * @var Team[] $teams
     *
     * @ORM\ManyToMany(targetEntity="Team", inversedBy="users")
     * @ORM\JoinTable(name="team_to_user")
     */
    private $teams;

    /**
     * @var \DataDog\GoalBundle\Entity\Achievement[] $achievements
     *
     * @ORM\OneToMany(targetEntity="\DataDog\GoalBundle\Entity\Achievement", mappedBy="user")
     */
    private $achievements;

    /**
     * @var \DataDog\GoalBundle\Entity\Achievement[] $managedAchievements
     *
     * @ORM\OneToMany(targetEntity="\DataDog\GoalBundle\Entity\Achievement", mappedBy="manager")
     */
    private $managedAchievements;

    /**
     * @var string
     */
    private $plainPassword;

    public function __construct() {
        $this->managedTeams = new ArrayCollection();
        $this->teams = new ArrayCollection();
    }

    /**
     * @param string $plainPassword
     * @return bool|string
     */
    private function encryptPassword($plainPassword)
    {
        $result = password_hash($plainPassword, PASSWORD_BCRYPT, ['cost' => 13, 'salt' => 'mySup3r4w3someS3cr3tMegaSaltBkamp']);
        return $result;
    }
    /**
     * @param string $plainPassword
     * @return bool
     */
    public function validatePassword($plainPassword)
    {
        $encrypted = $this->encryptPassword($plainPassword);
        return $encrypted === $this->getPassword();
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
    public function getRoleId()
    {
        return $this->role_id;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return [$this->getRole()->getRole()];
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {

    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password_hash,
            //$this->hashSalt,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password_hash,
            //$this->hashSalt,
            ) = unserialize($serialized);
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string #plainText
     */
    public function setPassword($plainText)
    {
        if($plainText) {
            $this->password_hash = $this->encryptPassword($plainText);
        }
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password_hash;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    /**
     * @return int
     */
    public function getTotalPoints()
    {
        return $this->total_points;
    }

    /**
     * @param int $total_points
     */
    public function setTotalPoints($total_points)
    {
        $this->total_points = $total_points;
    }

    public function addPoints($points){
        $this->total_points += $points;
    }

    public function getAchievementCount(){
        return count($this->achievements);
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
     * @return int
     */
    public function getLoginAt()
    {
        return $this->login_at;
    }

    /**
     * @param int $login_at
     */
    public function setLoginAt($login_at)
    {
        $this->login_at = $login_at;
    }

    /**
     * @return UserRole
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param UserRole $role
     */
    public function setRole($role)
    {
        $this->role = $role;
        $this->role_id = $role->getId();
    }

    /**
     * @return Team[]
     */
    public function getManagedTeams()
    {
        return $this->managedTeams;
    }

    /**
     * @param Team[] $managedTeams
     */
    public function setManagedTeams($managedTeams)
    {
        $this->managedTeams = $managedTeams;
    }

    public function addManagedTeam(Team $managedTeam){
        $managedTeam->setManager($this);
        $this->managedTeams[] = $managedTeam;
    }

    /**
     * @return Team[]
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @param Team[] $teams
     */
    public function setTeams($teams)
    {
        $this->teams = $teams;
    }

    public function addTeam(Team $team){
        $this->teams[] = $team;
    }

    /**
     * @return \DataDog\GoalBundle\Entity\Achievement[]
     */
    public function getAchievements()
    {
        return $this->achievements;
    }

    /**
     * @param \DataDog\GoalBundle\Entity\Achievement[] $achievements
     */
    public function setAchievements($achievements)
    {
        $this->achievements = $achievements;
    }

    public function addAchievement($achievement){
        $this->achievements[] = $achievement;
    }

    /**
     * @return \DataDog\GoalBundle\Entity\Achievement[]
     */
    public function getManagedAchievements()
    {
        return $this->managedAchievements;
    }

    /**
     * @param \DataDog\GoalBundle\Entity\Achievement[] $managedAchievements
     */
    public function setManagedAchievements($managedAchievements)
    {
        $this->managedAchievements = $managedAchievements;
    }

    public function addManagedAchievement($managedAchievement){
        $this->managedAchievements[] = $managedAchievement;
    }

    public function getManagedUsers(){
        if($this->getRole()->getRole() !== 'ROLE_MANAGER'){
            return null;
        }
        $users = [];
        foreach($this->managedTeams as $team){
            $newUsers = $team->getUsers();
            if(count($newUsers) === 1){
                $selection[] = $newUsers;
            }
            $selection = $newUsers;
            $users = array_merge($users, $selection);
        }
        if(empty($users)){
            $users = null;
        }
        return $users;
    }



    public function __toString(){
        $displayName = '';
        if($this->first_name){
            $displayName .= $this->first_name;
        }
        if($this->last_name){
            if($displayName){
                $displayName .= ' '.$this->last_name;
            }else{
                $displayName .= $this->last_name;
            }
        }
        $displayName .= '('.$this->username.')';
        return $displayName;
    }


}