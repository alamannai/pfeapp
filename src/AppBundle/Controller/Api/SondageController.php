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
use AppBundle\Entity\Sondage;
use AppBundle\Entity\LimiteSondage;
use AppBundle\Entity\Participation;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\JsonSerializable;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


  /**
     * @Route("api/commune/{commune}/sondages")
     * 
     */  

class SondageController extends Controller
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
        
        if ($commune){
            $em = $this->getDoctrine()->getManager();
            $sondages = $em->getRepository('AppBundle:Sondage')->findBy(['commune' => $commune]);

            if ($sondages) {
                    $l1=null;
                    $l2=null;
                foreach ($sondages as $sondage) {
                    $pour=0;
                    $contre=0;
                    
                    $em = $this->getDoctrine()->getManager();
                    $participations = $em->getRepository('AppBundle:Participation')->findBy( [ 'sondage'=>$sondage->getId()]);
                    $em = $this->getDoctrine()->getManager();
                    $sond = $em->getRepository('AppBundle:LimiteSondage')->findOneBy( [ 'sondage'=>$sondage->getId()]);

                    foreach ($participations as $participation) {
                       if ($participation->getType()== true) {
                           $pour++;
                       }else{
                        $contre++;
                       }
                    }
                
                    $onesondage=array(
                        'id' =>$sondage->getId(),
                        'description'=>$sondage->getDescription(),
                        'pour'=>$pour,
                        'contre'=>$contre,
                        'fin'=>$sond->getFin()
                    );

                if ($sondage->getTermine() ) {
                    $l2[]=$onesondage;
                } else{
                    $l1[]=$onesondage;
                }
            }
            $list=array(
                'arch'=>$l2,
                'nonarch'=>$l1
            );

                $listepro= array(
                    'status' => true ,
                    'data' => $list,
                    'msg' => ' La liste des sondages'


                );
            
            
            }else{
            $listepro=array(
                'status' => true ,
                'data' => '',
                'msg' => 'Aucun sondage'

                );
        }

        }else{
            $listepro=array(
                'status' => false ,
                'data' => '',
                'msg' => 'Invalide Commune'

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
        
        if ($commune){

        $em=$this->getDoctrine()->getManager();
        $sondage=$em->getRepository('AppBundle:Sondage')->findOneBy(['commune'=> $commune, 'id'=> $id]);

        

         if ($sondage) {
                  
              
                    $pour=0;
                    $contre=0;
                    
                    $em = $this->getDoctrine()->getManager();
                    $participations = $em->getRepository('AppBundle:Participation')->findBy( [ 'sondage'=>$sondage->getId()]);
                    $em = $this->getDoctrine()->getManager();
                    $sond = $em->getRepository('AppBundle:LimiteSondage')->findOneBy( [ 'sondage'=>$sondage->getId()]);

                    foreach ($participations as $participation) {
                       if ($participation->getType()== true) {
                           $pour++;
                       }else{
                        $contre++;
                       }
                    }
                
                    $onesondage=array(
                        'id' =>$sondage->getId(),
                        'description'=>$sondage->getDescription(),
                        'pour'=>$pour,
                        'contre'=>$contre,
                        'fin'=>$sond->getFin()
                    );

            
            

                $listepro= array(
                    'status' => true ,
                    'data' => $onesondage,
                    'msg' => 'Sondage'


                );
            
            
            }else{
            $listepro=array(
                'status' => true ,
                'data' => '',
                'msg' => 'Aucun sondage'

                );
        }
           
        }
        else{
           $listepro= array(
                'status' => false ,
                'data' => '',
                'msg' => 'Aucune Commune'

                );
        }
    
        
        $response = $serializer->serialize($listepro, 'json');
        return new Response($response);



    }


  


}
