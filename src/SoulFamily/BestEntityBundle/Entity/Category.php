<?php

namespace SoulFamily\BestEntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="SoulFamily\BestEntityBundle\Repository\CategoryRepository")
 */
class Category
{

    const BOOKS = 1;
    const FILMS = 2;
    const PLACES = 3;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=250)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="img_url", type="string", length=250)
     */
    private $imgUrl;


    /**
     * @ORM\OneToMany(targetEntity="EntityDescription", mappedBy="category")
     */
    private $bestEntities;

    /**
     * Category constructor.
     */
    public function __construct()
    {
        $this->bestEntities = new ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     *
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Category
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set image url
     *
     * @param string $imgUrl
     *
     * @return Category
     */
    public function setImgUrl($imgUrl)
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }

    /**
     * Get image url
     *
     * @return string
     */
    public function getImgUrl()
    {
        return $this->imgUrl;
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
}

