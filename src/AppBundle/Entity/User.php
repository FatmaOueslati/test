<?php

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**

 * @ORM\Entity

 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")

 */

class User implements UserInterface

{

    /**

     * @ORM\Id

     * @ORM\GeneratedValue

     * @ORM\Column(type="integer")

     */

    private $id;

    /**

     * @ORM\Column(type="string", unique=true)

     */

    private $username;

    /**

     * @ORM\Column(type="string", unique=true)

     */

    private $email;

    /**

     * @ORM\Column(type="string")

     */

    private $password;

    /**

     * @ORM\Column(type="string")

     */

    private $passwordConfirmation;

    /**
     * @return mixed
     */
    public function getPasswordConfirmation()
    {
        return $this->passwordConfirmation;
    }

    /**
     * @param mixed $passwordConfirmation
     */
    public function setPasswordConfirmation($passwordConfirmation)
    {
        $this->passwordConfirmation = $passwordConfirmation;
    }

    /**

     * @ORM\Column(type="json_array")

     */

    private $roles = array();



    public function getId()

    {

        return $this->id;

    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }



    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }


    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }



    /**

     * Returns the roles or permissions granted to the user for security.

     */

    public function getRoles()

    {
        $roles = $this->roles;

        // guarantees that a user always has at least one role for security

        if (empty($roles)) {

            $roles[] = 'ROLE_USER';
            $roles[] = 'ROLE_ADMIN';

        }

        return array_unique($roles);

    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }


    /**

     * Returns the salt that was originally used to encode the password.

     */

    public function getSalt()

    {
        return;

    }

    /**

     * Removes sensitive data from the user.

     */

    public function eraseCredentials()

    {

        // if you had a plainPassword property, you'd nullify it here

        // $this->plainPassword = null;

    }



}