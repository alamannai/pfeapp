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
use AppBundle\Entity\vote;
use AppBundle\Entity\Projet;
use AppBundle\Entity\Citoyen;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\JsonSerializable;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * 
 *
 * @Route("api/projets/vote")
 */


class VoteController extends Controller
{
    /**
     * @Route("/")
     * @Method("GET")
     */
    public function listAction(Request $request)
    {
        $projet_id=$request->query->get('projet');
        $liste=null;

        $encoders = array( new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        
        $em = $this->getDoctrine()->getManager();
        $votes = $em->getRepository('AppBundle:Vote')->findBy(['projet' => $projet_id]);

        foreach ($votes as $vote ) {
            $nom=$vote->getCitoyen()->getNom();
            $prenom=$vote->getCitoyen()->getPrenom();
            $liste[]=$prenom.' '.$nom;
        }
        

        
        $response = $serializer->serialize($liste, 'json');
         
        
        return new Response($response);
    
    }

 
    /**
     *
     * @Route("/{id}")
     * @Method("GET")
     */
    public function showProjet($id)
    {
     

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        
        $em=$this->getDoctrine()->getManager();
        $vote=$em->getRepository('AppBundle:Vote')->find($id);


       
        
        $response = $serializer->serialize($vote, 'json');
        return new Response($response);



    }


  


}
