<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table()
 */
class UserListWord
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="UserList", inversedBy="userListWords")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     * @var UserList
     */
    private $userList;

    /**
     * @ORM\ManyToOne(targetEntity="Word")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     * @var Word
     */
    private $word;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $learned = false;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return UserList
     */
    public function getUserList()
    {
        return $this->userList;
    }

    /**
     * @param UserList $userList
     */
    public function setUserList($userList)
    {
        $this->userList = $userList;
    }

    /**
     * @return Word
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * @param Word $word
     */
    public function setWord($word)
    {
        $this->word = $word;
    }

    /**
     * @return boolean
     */
    public function isLearned()
    {
        return $this->learned;
    }

    /**
     * @param boolean $learned
     */
    public function setLearned($learned)
    {
        $this->learned = $learned;
    }
}