<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EtatProjet
 *
 * @ORM\Table(name="etat_projet")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EtatProjetRepository")
 */
class EtatProjet
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
     * @ORM\Column(name="reason", type="string", length=255, nullable=true)
     */
    private $reason;

 

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
     * Set reason
     *
     * @param string $reason
     *
     * @return EtatProjet
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }

    /**
     * Get reason
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }


    /**
    * @ORM\OneToOne(targetEntity="Projet", cascade={"persist"})
    */
      private $projet;


      public function getEtat()
    {
        return $this->projet;
    }
     public function setProjet($projet)
    {
        $this->projet = $projet;

        return $this;
    }
    
}

