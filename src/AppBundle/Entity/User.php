<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="date")
     */
    protected $birthday;

    /**
     * @var Article
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Article", mappedBy="user")
     */
    protected $article;

    public function __construct ()
    {
        parent::__construct();
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
     * @return \DateTime
     */
    public function getBirthday ()
    {
        return $this->birthday;
    }

    /**
     * @param \DateTime $birthday
     */
    public function setBirthday ($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * @return Article
     */
    public function getArticle ()
    {
        return $this->article;
    }

    /**
     * @param Article $article
     */
    public function setArticle ($article)
    {
        $this->article = $article;
    }
}

