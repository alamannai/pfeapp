<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Reclamation
 *
 * @ORM\Table(name="reclamations")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReclamationRepository")
 */
class Reclamation
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
     * @ORM\Column(name="contenu", type="string", length=700)
     */
    private $contenu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="horizontale", type="integer", nullable=true)
     */
    private $horizontale;

    /**
     * @var int
     *
     * @ORM\Column(name="verticale", type="integer", nullable=true)
     */
    private $verticale;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=400, nullable=true)
     * @Assert\NotBlank(message="Please, upload ")
     */
    private $image;

    /**
     * @var bool
     *
     * @ORM\Column(name="closed", type="boolean")
     */
    private $closed;


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
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Reclamation
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Reclamation
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
     * Set horizontale
     *
     * @param integer $horizontale
     *
     * @return Reclamation
     */
    public function setHorizontale($horizontale)
    {
        $this->horizontale = $horizontale;

        return $this;
    }

    /**
     * Get horizontale
     *
     * @return int
     */
    public function getHorizontale()
    {
        return $this->horizontale;
    }

    /**
     * Set verticale
     *
     * @param integer $verticale
     *
     * @return Reclamation
     */
    public function setVerticale($verticale)
    {
        $this->verticale = $verticale;

        return $this;
    }

    /**
     * Get verticale
     *
     * @return int
     */
    public function getVerticale()
    {
        return $this->verticale;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Reclamation
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

     /**
     * Set closed
     *
     * @param boolean $closed
     *
     */
    public function setClosed($closed)
    {
        $this->closed = $closed;

    }

    /**
     * Get closed
     *
     * @return bool
     */
    public function getClosed()
    {
        return $this->closed;
    }



     /**
     * @ORM\ManyToOne(targetEntity="Citoyen")
     * @ORM\JoinColumn(name="citoyen_id", referencedColumnName="id")
     */
    private $citoyen;

    public function getCitoyen()
   {
     return $this->citoyen;
   }

   public function setCitoyen(Citoyen $citoyen)
    {   
        $this->citoyen = $citoyen;
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

