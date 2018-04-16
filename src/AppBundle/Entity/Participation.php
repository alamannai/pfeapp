<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participation
 *
 * @ORM\Table(name="participations")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ParticipationRepository")
 */
class Participation
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
     * @var bool
     *
     * @ORM\Column(name="type", type="boolean")
     */
    private $type;


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
     * Set type
     *
     * @param boolean $type
     *
     */
    public function setType($type)
    {
        $this->type = $type;

    }

    /**
     * Get type
     *
     * @return bool
     */
    public function getType()
    {
        return $this->type;
    }


     /**
     * @ORM\ManyToOne(targetEntity="Sondage", inversedBy="participations")
     * @ORM\JoinColumn(name="sondage_id", referencedColumnName="id")
     */
    private $sondage;

    public function getSondage()
   {
     return $this->sondage;
   }

   public function setSondage( $sondage)
    {   
        $this->sondage = $sondage;
    }



    /**
     * @ORM\ManyToOne(targetEntity="Citoyen", inversedBy="participations")
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
     * @var \DateTime
     *
     * @ORM\Column(name="datep", type="datetime")
     */
    private $datep;

    /**
     * Set datep
     *
     * @param \DateTime $datep
     *
     */
    public function setDatep($datep)
    {
        $this->datep = $datep;

    }

    /**
     * Get datep
     *
     * @return \DateTime
     */
    public function getDatep()
    {
        return $this->datep;
    }
}

