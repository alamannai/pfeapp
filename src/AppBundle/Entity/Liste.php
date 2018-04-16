<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ListeCitoyens
 *
 * @ORM\Table(name="listes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ListeRepository")
 */
class Liste
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Commune", inversedBy="liste")
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

   /**
     * @ORM\ManyToOne(targetEntity="Citoyen", inversedBy="liste")
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
     * @var bool
     *
     * @ORM\Column(name="blocked", type="boolean")
     */
    private $blocked;
    /**
     * Set blocked
     *
     * @param boolean $blocked
     *
     * @return Liste
     */
    public function setBlocked($blocked)
    {
        $this->blocked = $blocked;

    }

    /**
     * Get blocked
     *
     * @return bool
     */
    public function getBlocked()
    {
        return $this->blocked;
    }
}

