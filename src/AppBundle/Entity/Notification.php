<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table(name="notifications")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NotificationRepository")
 */
class Notification
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
     * @ORM\Column(name="contenu", type="string", length=255)
     */
    private $contenu;

    /**
     * @var bool
     *
     * @ORM\Column(name="vue", type="boolean")
     */
    private $vue;

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
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Notification
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
     * Set vue
     *
     * @param boolean $vue
     *
     * @return Notification
     */
    public function setVue($vue)
    {
        $this->vue = $vue;

        return $this;
    }

    /**
     * Get vue
     *
     * @return bool
     */
    public function getVue()
    {
        return $this->vue;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Notification
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
     return $this->commune->getId();
   }
   public function setCommune( $commune)
    {   
        $this->commune = $commune;
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
}

