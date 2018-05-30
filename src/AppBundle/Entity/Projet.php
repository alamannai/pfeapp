<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Projet
 *
 * @ORM\Table(name="projets")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjetRepository")
 */
class Projet
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
     * @ORM\Column(name="sujet", type="string", length=255)
     */
    private $sujet;

    /**
     * @var text
     *
     * @ORM\Column(name="contenu", type="text", length=4000)
     */
    private $contenu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="date")
     */
    private $dateDebut;

    /**
     * @var string
     *
     * @ORM\Column(name="duree", type="string", length=255)
     */
    private $duree;

 


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
     * Set sujet
     *
     * @param string $sujet
     *
     * @return Projet
     */
    public function setSujet($sujet)
    {
        $this->sujet = $sujet;

        return $this;
    }

    /**
     * Get sujet
     *
     * @return string
     */
    public function getSujet()
    {
        return $this->sujet;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Projet
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
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Projet
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set duree
     *
     * @param string $duree
     *
     * @return Projet
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return string
     */
    public function getDuree()
    {
        return $this->duree;
    }

    
     /**
     * @ORM\OneToMany(targetEntity="Vote", mappedBy="projet")
     */
    private $votes;

    

 
    public function getVotes()
    {
        return $this->votes;
    }

    /**
     * @ORM\OneToMany(targetEntity="Commentaire", mappedBy="projet")
     */
    private $commentaires;

    

 
    public function getCommentaires()
    {
        $comms=$this->commentaires;
        $c=0;
        foreach ( $comms as $commentaire) {
        
        if ($commentaire->getValidation()==true) {
            $c++;
        }
    }
    return $c;
}

public function getAllommentaires()
    {
       
    return $this->commentaires;
}



     /**
     * @ORM\ManyToOne(targetEntity="Commune", inversedBy="projets")
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
     * @var bool
     *
     * @ORM\Column(name="done", type="string",nullable=true, length=1)
     */
    private $done;

    /**
     * Set done
     *
     *
     */
    public function setDone($done)
    {
        $this->done = $done;

    }

    /**
     * Get done
     *
     */
    public function getDone()
    {
        return $this->done;
    }




    /**
     * @Assert\DateTime()
     */
     protected $createdAt;



  /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload ")
     */
    private $image;

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }
      
}

