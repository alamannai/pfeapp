<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sondage
 *
 * @ORM\Table(name="sondages")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SondageRepository")
 */
class Sondage
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
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;




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
     * Set description
     *
     * @param string $description
     *
     * @return Sondage
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    


     /**
     * @ORM\OneToMany(targetEntity="Participation", mappedBy="sondage")
     */
    private $participations;

    

 
    public function getParticipations()
    {
        return $this->participations;
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


   /**
    * @ORM\OneToOne(targetEntity="LimiteSondage", cascade={"persist"})
    */
      private $limite;





    /**
     * @var bool
     *
     * @ORM\Column(name="termine", type="boolean")
     */
    private $termine;

    /**
     * Set termine
     *
     * @param bool $termine
     *
     */
    public function setTermine($termine)
    {
        $this->termine = $termine;

    }

    /**
     * Get termine
     *
     * @return bool
     */
    public function getTermine()
    {
        return $this->termine;
    }
}

