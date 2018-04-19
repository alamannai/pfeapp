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



class ChoixController extends Controller
{




    /**
     * @Route("api/gouvernorat/")
     * @Method("GET")
     */
    public function govlistAction(Request $request)
    {

        $encoders = array( new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        
        $em = $this->getDoctrine()->getManager();
        $gouvernorats = $em->getRepository('AppBundle:Gouvernorat')->findall();



        $rep=array(
            'status' => true ,
            'data' => $gouvernorats,
            'msg '=>'Liste des gouvernorats'
        );
       
        

        $response = $serializer->serialize($rep, 'json');
         
        
        return new Response($response);
    
    }

    /**
     * @Route("api/commune/")
     * @Method("GET")
     */
    public function listAction(Request $request)
    {

        $encoders = array( new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $gouvernorat=$request->query->get('gouvernorat');
        $em = $this->getDoctrine()->getManager();
        $gov = $em->getRepository('AppBundle:Gouvernorat')->find($gouvernorat);

        if ($gov) {
            $em = $this->getDoctrine()->getManager();
            $communes = $em->getRepository('AppBundle:Commune')->findBy(['gouvernorat'=>$gouvernorat]);

            if ($communes) {
               foreach ($communes as $commune) {
                $c=array(
                    'id'=>$commune->getId(),
                    'nom'=>$commune->getNom()
                );
                $liste[]=$c;
                
            }
            $rep=array(
                    'status' => true ,
                    'data' => $liste,
                    'msg' => 'Liste des communes'
                );
            }else{
                $rep=array(
                    'status' => false ,
                    'data' => '',
                    'msg' => 'Aucune commune'
                );
            }
        }else{
            $rep=array(
                    'status' => false ,
                    'data' => '',
                    'msg' => 'check'
                );
        }

        
        
        
        
        
        
            

        $response = $serializer->serialize($rep, 'json');
         
        
        return new Response($response);
    
    }



}