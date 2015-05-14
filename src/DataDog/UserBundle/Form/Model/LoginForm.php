<?php
namespace DataDog\UserBundle\Form\Model;
use Symfony\Component\Validator\Constraints as Assert;
use DataDog\UserBundle\Entity\User;
/**
 * Class LoginForm
 * @package DataDog\UserBundle\Form\Model
 */
class LoginForm
{
    /**
     * @Assert\Type(type="DataDog\UserBundle\Entity\User")
     * @Assert\Valid()
     */
    protected $user;
    /**
     * @Assert\NotBlank()
     */
    protected $username;
    /**
     * @Assert\NotBlank()
     */
    protected $plainPassword;
    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
    /**
     * @param string $email
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }
    /**
     * @param string $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }
}