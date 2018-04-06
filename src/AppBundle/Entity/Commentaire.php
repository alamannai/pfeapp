<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaires")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentaireRepository")
 */
class Commentaire
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
     * @ORM\Column(name="validation", type="boolean")
     */
    private $validation;


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
     * @return Commentaire
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

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
     * Set validation
     *
     * @param boolean $validation
     *
     * @return Commentaire
     */
    public function setValidation($validation)
    {
        $this->validation = $validation;

    }

    /**
     * Get validation
     *
     * @return bool
     */
    public function getValidation()
    {
        return $this->validation;
    }



   


    /**
     * @ORM\ManyToOne(targetEntity="Projet", inversedBy="commentaires")
     * @ORM\JoinColumn(name="projet_id", referencedColumnName="id")
     */
    private $projet;

    public function getProjet()
   {
     return $this->projet;
   }

   public function setProjet(Projet $projet)
    {   
        $this->projet = $projet;
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

