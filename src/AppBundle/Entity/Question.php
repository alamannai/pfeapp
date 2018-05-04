<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 *
 * @ORM\Table(name="questions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QuestionRepository")
 */
class Question
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
     * @ORM\Column(name="questionfield", type="string", length=2000)
     */
    private $questionfield;

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
     * Set questionfield
     *
     * @param string $questionfield
     *
     * @return Question
     */
    public function setQuestionfield($questionfield)
    {
        $this->questionfield = $questionfield;

        return $this;
    }

    /**
     * Get questionfield
     *
     * @return string
     */
    public function getQuestionfield()
    {
        return $this->questionfield;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Question
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

     /**
    * @ORM\OneToOne(targetEntity="Reponse", cascade={"persist"})
    */
    private $reponse;
    
    public function getReponse()
   {
     return $this->reponse;
   }

   public function setReponse( $reponse)
    {   
        $this->reponse = $reponse;
    }
}

