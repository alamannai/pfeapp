<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reponnse
 *
 * @ORM\Table(name="reponses")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReponseRepository")
 */
class Reponse
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
     * @ORM\Column(name="reponsefield", type="string", length=2000)
     */
    private $reponsefield;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;


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
     * Set reponsefield
     *
     * @param string $reponsefield
     *
     * @return Reponnse
     */
    public function setReponsefield($reponsefield)
    {
        $this->reponsefield = $reponsefield;

        return $this;
    }

    /**
     * Get reponsefield
     *
     * @return string
     */
    public function getReponsefield()
    {
        return $this->reponsefield;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Reponnse
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }


     /**
     * @ORM\ManyToOne(targetEntity="Commune")
     * @ORM\JoinColumn(name="commune_id", referencedColumnName="id")
     */
    private $commune;

    public function getCommune()
   {
     return $this->commune;
   }

   public function setCommune(Commune $commune)
    {   
        $this->commune = $commune;
    }
}

