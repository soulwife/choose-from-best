<?php

namespace SoulFamily\BestEntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User_EntityLists
 *
 * @ORM\Table(name="user__entity_lists")
 * @ORM\Entity(repositoryClass="SoulFamily\BestEntityBundle\Repository\User_EntityListsRepository")
 */
class User_EntityLists
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="userid", type="integer")
     */
    private $userid;

    /**
     * @var int
     *
     * @ORM\Column(name="listid", type="integer")
     */
    private $listid;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userid
     *
     * @param integer $userid
     *
     * @return User_EntityLists
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Get userid
     *
     * @return int
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Set listid
     *
     * @param integer $listid
     *
     * @return User_EntityLists
     */
    public function setListid($listid)
    {
        $this->listid = $listid;

        return $this;
    }

    /**
     * Get listid
     *
     * @return int
     */
    public function getListid()
    {
        return $this->listid;
    }
}

