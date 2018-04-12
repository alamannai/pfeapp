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
 * @Route("api/commune")
 */


class ChoixController extends Controller
{




    /**
     * @Route("/")
     * @Method("GET")
     */
    public function govlistAction(Request $request)
    {

        $encoders = array( new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        
        $em = $this->getDoctrine()->getManager();
        $gouvernorats = $em->getRepository('AppBundle:Commune')->findall();


        foreach ($gouvernorats as $gouvernorat) {
            $govi= $gouvernorat->getGouvernorat();
            $listi[]=$govi;
        }
        
        $ng=$listi[0];
        $listf[]=$ng;

        for ($i=1; $i <count($gouvernorats) ; $i++) { 
            if (!($ng==$listi[$i])) {
                $listf[]=$listi[$i];
            }
            else{$ng=$listi[$i];
            }
        }

        $rep=array(
            'status' => true ,
            'data' => $listf,
            'msg' => 'les gouvernorats existants'
        );
            

        $response = $serializer->serialize($rep, 'json');
         
        
        return new Response($response);
    
    }

    /**
     * @Route("/{gouvernorat}")
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
        
        if (!empty($communes)) {
            foreach ($communes as $commune  ) {
               $listecommune[]=array(
                'id'=> $commune->getId(),
               'nom' => $commune->getNom()
           );

        }
            $rep=array(
                'status' => true ,
                'data' => $listecommune,
                'msg' => 'La liste des communes'
            );
        }else{
            $rep=array(
                'status' => false ,
                'data' => '',
                'msg' => 'Aucune commune'
            );
        }
        
            

        $response = $serializer->serialize($rep, 'json');
         
        
        return new Response($response);
    
    }



    /**
     *
     * @Route("/{id}")
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
               'nom' => $commune->getNom(),
               'gouvernorat' =>$commune->getGouvernorat()
           );
        
        $response = $serializer->serialize($listecommune, 'json');
        return new Response($response);



    }


}