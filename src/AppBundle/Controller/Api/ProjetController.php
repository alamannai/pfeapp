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


  /**
     * @Route("api/commune/{commune}/projets")
     * 
     */  

class ProjetController extends Controller
{
    /**
     * @Route("/")
     * @Method("GET")
     */
    public function listAction(Request $request,$commune)
    {
        $em = $this->getDoctrine()->getManager();
        $commune = $em->getRepository('AppBundle:Commune')->find( $commune);

        $encoders = array( new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        
        if (!(empty($commune))){
            $em = $this->getDoctrine()->getManager();
            $projets = $em->getRepository('AppBundle:Projet')->findBy(['commune' => $commune]);

            if (!(empty($projets))) {

                $listepro= array(
                    'status ' => true ,
                    'data ' => $projets,
                    'msg ' => ' La liste des projets'


                );
            
            
            }else{
            $listepro=array(
                'status ' => true ,
                'data ' => '',
                'msg ' => 'Aucun projet'

                );
        }

        }else{
            $listepro=array(
                'status ' => false ,
                'data ' => '',
                'msg ' => 'Aucune Commune'

                );
        }

        

        $response = $serializer->serialize($listepro, 'json');
         
        
        return new Response($response);
    
    }

 
    /**
     *
     * @Route("/{id}")
     * @Method("GET")
     */
    public function showProjet($id,Request $request,$commune)
    {
        $em = $this->getDoctrine()->getManager();
        $commune = $em->getRepository('AppBundle:Commune')->find( $commune);


        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        
        if (!(empty($commune))){

        $em=$this->getDoctrine()->getManager();
        $projet=$em->getRepository('AppBundle:Projet')->findBy(['commune'=> $commune, 'id'=> $id]);

        

        if (!(empty($projet))) {

            $listepro= array(
                    'status ' => true ,
                    'data ' => $projet,
                    'msg ' => ' Le projet'


                );
        }else{
            $listepro=array(
                'status ' => false ,
                'data ' => '',
                'msg ' => 'Aucun projet'

                );
        }
           
        }
        else{
           $listepro= array(
                'status ' => false ,
                'data ' => '',
                'msg ' => 'Aucune Commune'

                );
        }
    
        
        $response = $serializer->serialize($listepro, 'json');
        return new Response($response);



    }


  


}
