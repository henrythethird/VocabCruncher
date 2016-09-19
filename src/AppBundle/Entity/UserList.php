<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class UserList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="User")
     * @var User
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="UserListWord", mappedBy="userList")
     * @var ArrayCollection
     */
    private $userListWords;

    public function __construct()
    {
        $this->userListWords = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return ArrayCollection
     */
    public function getUserListWords()
    {
        return $this->userListWords;
    }

    /**
     * @param ArrayCollection $userListWords
     */
    public function setUserListWords($userListWords)
    {
        $this->userListWords = $userListWords;
    }
}