<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Token
 *
 * @ORM\Table(name="token")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TokenRepository")
 */
class Token
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
     * @ORM\Column(name="tokenfield", type="string", length=1000, nullable=true)
     */
    private $tokenfield;


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
     * Set tokenfield
     *
     * @param string $tokenfield
     *
     * @return Token
     */
    public function setTokenfield($tokenfield)
    {
        $this->tokenfield = $tokenfield;

        return $this;
    }

    /**
     * Get tokenfield
     *
     * @return string
     */
    public function getTokenfield()
    {
        return $this->tokenfield;
    }


    /**
    * @ORM\OneToOne(targetEntity="Citoyen", cascade={"persist"})
    */
    private $citoyen;
    
    public function getCitoyen()
   {
     return $this->citoyen;
   }

   public function setCitoyen( $citoyen)
    {   
        $this->citoyen = $citoyen;
    }
}

