<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Projet;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\JsonSerializable;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;




class ProjetController extends Controller
{
    /**
     * @Route("api/commune/{commune}/projets/")
     * @Method("GET")
     */
    public function listAction(Request $request,$commune)
    {
        $listepro=null;

        $encoders = array( new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        
        $em = $this->getDoctrine()->getManager();
        $projets = $em->getRepository('AppBundle:Projet')->findBy(['commune' => $commune]);

        if (!(empty($projets))) {
            $listepro= $projets;
        }

        $response = $serializer->serialize($listepro, 'json');
         
        
        return new Response($response);
    
    }

 
    /**
     *
     * @Route("api/commune/{commune}/projets/{id}")
     * @Method("GET")
     */
    public function showProjet($id,Request $request,$commune)
    {
        $commune_id=$commune;
        $listepro=null;

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        
        $em=$this->getDoctrine()->getManager();
        $projet=$em->getRepository('AppBundle:Projet')->findBy(['commune' => $commune_id,'id' =>$id]);


        if (!(empty($projet))) {

            $listepro=$projet;
           
        }
        
        $response = $serializer->serialize($listepro, 'json');
        return new Response($response);



    }


  


}
