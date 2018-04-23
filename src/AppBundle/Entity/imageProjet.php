<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * imageProjet
 *
 * @ORM\Table(name="image_projet")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\imageProjetRepository")
 */
class imageProjet
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
     * @ORM\Column(type="string" )
     *
     * @Assert\NotBlank(message="an image")
     * @Assert\Image()
     */

    private $image;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

   public function setImage(File $image )
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }
}

