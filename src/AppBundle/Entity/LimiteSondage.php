<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LimiteSondage
 *
 * @ORM\Table(name="limite_sondage")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LimiteSondageRepository")
 */
class LimiteSondage
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
     * @var \DateTime
     *
     * @ORM\Column(name="fin", type="datetime")
     */
    private $fin;


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
     * Set fin
     *
     * @param \DateTime $fin
     *
     * @return LimiteSondage
     */
    public function setFin($fin)
    {
        $this->fin = $fin;

        return $this;
    }

    /**
     * Get fin
     *
     * @return \DateTime
     */
    public function getFin()
    {
        return $this->fin;
    }


    /**
    * @ORM\OneToOne(targetEntity="Sondage", cascade={"persist"})
    */
      private $sondage;


      public function getSondage()
    {
        return $this->sondage;
    }
}

