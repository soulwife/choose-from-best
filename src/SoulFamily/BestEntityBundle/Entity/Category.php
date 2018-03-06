<?php

namespace SoulFamily\BestEntityBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection as ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="SoulFamily\BestEntityBundle\Repository\CategoryRepository")
 */
class Category
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=250)
     * @Assert\NotBlank(message="The url cannot be empty!")
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="img_url", type="string", length=250)
     * @Assert\NotBlank(message="The img url cannot be empty!")
     */
    private $imgUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="html_crawl_path", type="string", length=250)
     * @Assert\NotBlank(message="The html crawl path cannot be empty!")
     */
    private $htmlCrawlPath;


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
     * Set htmlCrawlPath
     *
     * @param string $htmlCrawlPath
     *
     * @return Category
     */
    public function setHtmlCrawlPath($htmlCrawlPath)
    {
        $this->htmlCrawlPath = $htmlCrawlPath;

        return $this;
    }

    /**
     * Get htmlCrawlPath
     *
     * @return string
     */
    public function getHtmlCrawlPath()
    {
        return $this->htmlCrawlPath;
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

