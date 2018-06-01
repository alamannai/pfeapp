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
use AppBundle\Entity\Citoyen;
use AppBundle\Entity\Notification;
use AppBundle\Entity\Liste;
use AppBundle\Form\ListeType;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\JsonSerializable;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


    /**
     * @Route("api/notification")
     */

class NotificationApiController extends Controller
{

 /**
     * @Route("/vue/{id}")
     * @Method("POST")
     */
    public function vueOneAction(Request $request,$id)
    {

        $encoders = array( new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);


        $token=$request->request->get('token') ;



        if ($token) {


        

        $e = $this->getDoctrine()->getManager();
        $notif = $e->getRepository('AppBundle:Notification')->find($id);

        $notif->setVue(true);


        
        $rep=array(
                                        'status' => true ,
                                        'data' => '',
                                        'msg'=> 'Vue notification'
                                      );
          
           
          }else{
            $rep=array(
                                        'status' => false ,
                                        'data' => '',
                                        'msg'=> 'Pas de connexion '
                                      );
          }
        $response = $serializer->serialize($rep, 'json');
        return new Response($response);
        
    




  }


/**
     * @Route("/vue/")
     * @Method("POST")
     */
    public function vueAllAction(Request $request)
    {

        $encoders = array( new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);


        $token=$request->request->get('token') ;



        if ($token) {


        
          $log = $em->getRepository('AppBundle:Token')->findOneBy([ 'tokenfield'=>$token]);
        $citoyen=$log->getCitoyen();

        $e = $this->getDoctrine()->getManager();
        $notifs = $e->getRepository('AppBundle:Notification')->findBy(['citoyen'=>$citoyen, 'vue'=>false]);

        foreach ($notifs as $notif) {
        $notif->setVue(true);
          
        }


        
        $rep=array(
                                        'status' => true ,
                                        'data' => '',
                                        'msg'=> 'Vue notification'
                                      );
          
           
          }else{
            $rep=array(
                                        'status' => false ,
                                        'data' => '',
                                        'msg'=> 'Pas de connexion '
                                      );
          }
        $response = $serializer->serialize($rep, 'json');
        return new Response($response);
        
    




  }






  /**
     * @Route("/")
     * @Method("GET")
     */
    public function listAction(Request $request)
    {

        $encoders = array( new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $token=$request->query->get('token') ;
        
        if ($token){
        $em = $this->getDoctrine()->getManager();
        $log = $em->getRepository('AppBundle:Token')->findOneBy([ 'tokenfield'=>$token]);
        $citoyen=$log->getCitoyen();

        $emm = $this->getDoctrine()->getManager();
        $notifs = $emm->getRepository('AppBundle:Notification')->findBy([ 'citoyen'=>$citoyen->getId(),'vue'=>false , 'destination'=> 'm']);
                  if ($notifs) {
                     foreach ($notifs as $notif ) {
                                  $x = $this->getDoctrine()->getManager();
                                  $comm = $x->getRepository('AppBundle:Commune')->find($notif->getCommune());

                               
                                  $liste[]=array(
                                    'id'=>$notif->getId(),
                                    'contenu'=>$notif->getContenu(),
                                    'commune'=> $comm->getNom(),
                                    'createdat'=>$notif->getCreatedAt(),
                                  );
                               

                        
                      }
                      $rep=array(
                          'status'=>true,
                          'data'=> $liste,
                          'msg'=>'Vos notifications'
                      );
                  }else{
                    $rep=array(
                          'status'=>true,
                          'data'=> '',
                          'msg'=>'Aucune notification'
                      );
                  }
           
        

        }else{
            $rep=array(
                'status' => false ,
                'data' => '',
                'msg' => 'Pas de connexion'

                );
        }

        

        $response = $serializer->serialize($rep, 'json');
         
        
        return new Response($response);
    
    }



 
}

