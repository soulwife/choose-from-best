<?php

namespace SoulFamily\BestEntityBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

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
}