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

    /*
    *@var string
    *
    *@ORM\column(name="image",type="string",length="255",nullable="true")
    */

    private $image;



    /*
    *@var file
    *
    *
    *@UploadableField(name="imagefile", path="uploads/imageProjet")
    */

    private $imagefile;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

   public function setImage( $image )
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }
}

