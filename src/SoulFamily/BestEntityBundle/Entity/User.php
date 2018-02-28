<?php

namespace SoulFamily\BestEntityBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Many User have Many Entities.
     * @ORM\ManyToMany(targetEntity="EntityDescription")
     * @ORM\JoinTable(name="users_entities",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="entity_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $bestEntities;

    public function __construct()
    {
        parent::__construct();
        $this->bestEntities = new ArrayCollection();
    }

    /**
     * Get best entities
     *
     * @return ArrayCollection
     */
    public function getBestEntities()
    {
        return $this->bestEntities;
    }

    /**
     * Set best entities
     *
     * @param $bestEntities
     * @return User
     */
    public function setBestEntities($bestEntities)
    {
        $this->bestEntities = $bestEntities;

        return $this;
    }
}