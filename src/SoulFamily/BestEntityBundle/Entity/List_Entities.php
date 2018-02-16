<?php

namespace SoulFamily\BestEntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * List_Entities
 *
 * @ORM\Table(name="user__entities")
 * @ORM\Entity(repositoryClass="SoulFamily\BestEntityBundle\Repository\List_EntitiesRepository")
 */
class List_Entities
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
     * @return List_Entities
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
     * Set entityid
     *
     * @param integer $entityid
     *
     * @return List_Entities
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
    public function getEntityid()
    {
        return $this->listid;
    }
}

