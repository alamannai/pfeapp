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
use AppBundle\Entity\Commune;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\JsonSerializable;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * 
 *
 * @Route("")
 */


class ChoixController extends Controller
{
    /**
     * @Route("api/commune/")
     * @Method("GET")
     */
    public function listAction(Request $request)
    {
        $gouvernorat=$request->query->get('gouvernorat');

        $encoders = array( new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        
        $em = $this->getDoctrine()->getManager();
        $communes = $em->getRepository('AppBundle:Commune')->findBy(['gouvernorat'=>$gouvernorat]);
        $listecommune=null;

        foreach ($communes as $commune  ) {
               $listecommune[]=array(
                'id'=> $commune->getId(),
               'nom' => $commune->getNom()
           );
            }

        $response = $serializer->serialize($listecommune, 'json');
         
        
        return new Response($response);
    
    }



    /**
     *
     * @Route("api/commune/{id}")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        
        $em=$this->getDoctrine()->getManager();
        $commune=$em->getRepository('AppBundle:Commune')->find($id);

        $listecommune=array(
                'id'=> $commune->getId(),
               'nom' => $commune->getNom()
           );
        
        $response = $serializer->serialize($listecommune, 'json');
        return new Response($response);



    }


}