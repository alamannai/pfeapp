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
use AppBundle\Entity\Liste;
use AppBundle\Form\ListeType;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\JsonSerializable;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


    /**
     * @Route("api/commune")
     */

class abonnerController extends Controller
{




    /**
     * @Route("/list/")
     * @Method("GET")
     */
    public function listcomAction(Request $request)
    {


        $encoders = array( new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $token=$request->request->get('token');
        
        if ($token) {

            $em = $this->getDoctrine()->getManager();
            $log = $em->getRepository('AppBundle:Token')->findOneBy([ 'tokenfield'=>$token]);
                    if ($log) {
                            $idc=$log->getCitoyen()->getId();

                            $em = $this->getDoctrine()->getManager();
                            $abs = $em->getRepository('AppBundle:Liste')->findBy([ 'citoyen'=>$idc]);
                                    if ($abs) {
                                       foreach ($abs as $ab ) {
                                        $c=array(
                                                'id'=>$ab->getCommune()->getId(),
                                                'nom'=>$ab->getCommune()->getNom()
                                            );
                                            $liste[]=$c;
                                    }
                                    $rep=array(
                                    'status' => true ,
                                    'data' => $liste,
                                    'msg' => 'Liste des communes'
                                );
                            }

                    }else{
                        $rep=array(
                                    'status' => false ,
                                    'data' =>'',
                                    'msg' => 'Invalide token'
                                );
                    }

                    
         }else{
            $rep=array(
                                    'status' => false ,
                                    'data' =>'',
                                    'msg' => 'Pas de connexion'
                                );
         }           
                        
       
        

        $response = $serializer->serialize($rep, 'json');
         
        
        return new Response($response);
    
    }

    /**
     * @Route("/ajout")
     * @Method("POST")
     */
    public function ajoutlistAction(Request $request)
    {

        $encoders = array( new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $token=$request->request->get('token') ;
        $commune=$request->request->get('commune') ;
        
        if ($token && $commune) {
            $em = $this->getDoctrine()->getManager();
            $log = $em->getRepository('AppBundle:Token')->findOneBy([ 'tokenfield'=>$token]);

                    if (!$log) {
                       $rep =array(
                                  'status' => false,  
                                  'data'=> '',
                                 'msg' => 'Pas de connexion'

                                 );
                    }else{
                        $idc=$log->getCitoyen()->getId();

                    $em = $this->getDoctrine()->getManager();
                    $com = $em->getRepository('AppBundle:Commune')->find($commune);

                    $em = $this->getDoctrine()->getManager();
                    $abs = $em->getRepository('AppBundle:Liste')->findBy([ 'citoyen'=>$idc, 'blocked'=> false]);

                    $em = $this->getDoctrine()->getManager();
                    $abcomm = $em->getRepository('AppBundle:Liste')->findOneBy(['commune'=> $commune,'citoyen'=>$idc]);

                        $val=true;
                        $blok=false;

                                if ((count($abs) < 3) && (count($abs) >=0)) {
                                    

                                        if ($abcomm) {
                                            $val=false;
                                            if ($abcomm->getBlocked()) {
                                                $blok=true;
                                            }

                                        }

                                    

                                    
                                            if ($val==true ) {
                                            $abonne= new Liste();
                                            $abonne->setCitoyen($log->getCitoyen());
                                            $abonne->setCommune($com);
                                            $abonne->setBlocked(false);

                                            $form = $this->createForm(ListeType::class, $abonne);
                                            $form->handleRequest($request);
                             
                                            $em->persist($abonne);
                                            $em->flush();

                                            $rep=array(
                                                'status'=>true,
                                                'data'=>'',
                                                'msg'=>'abonner avec succee'
                                            );
                                        }else{
                                                if ($blok) {
                                                            $rep=array(
                                                            'status'=>false,
                                                            'data'=>'',
                                                            'msg'=>'Commune introuvable'
                                                            );
                                                }else{
                                                        $rep=array(
                                                        'status'=>false,
                                                        'data'=>'',
                                                        'msg'=>'deja abonner a cette commune'
                                                        );
                                                }
                                                    

                                            
                                        }
                                        
                                    
                                    
                                    

                                }else{
                                    $rep=array(
                                        'status'=>false,
                                        'data'=>'',
                                        'msg'=>'deja abonner a 3 communes'
                                    );
                                }
                    }

            

        }else{
            $rep =array(
                          'status' => false,  
                          'data'=> '',
                         'msg' => 'Invalides parametres'

                         );
            }
        
        
        
        
        
            

        $response = $serializer->serialize($rep, 'json');
         
        
        return new Response($response);
    
    }




    /**
     * @Route("/delete/{commune}")
     * @Method("DELETE")
     */

    public function deleteCommune(Request $request,$commune)
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);


        $token=$request->headers->get('token');

        if (!$token) {
            $rep =array(
              'status' => false,  
              'data'=> '',
             'msg' => 'Pas de connexion '

             );
        }else{
                                 $em = $this->getDoctrine()->getManager();
                                $log = $em->getRepository('AppBundle:Token')->findOneBy([ 'tokenfield'=>$token]);
                                $citoyen=$log->getCitoyen();

                                 $em = $this->getDoctrine()->getManager();
                                $c = $em->getRepository('AppBundle:Commune')->find($commune);

                                                if (!$log) {
                                                    $rep =array(
                                                      'status' => false,  
                                                      'data'=> '',
                                                     'msg' => 'Invalide token '

                                                     );
                                                }else{

                                                          
                                                          $em = $this->getDoctrine()->getManager();
                                                          $ab = $em->getRepository('AppBundle:Liste')->findOneBy([ 'citoyen'=>$citoyen,'blocked'=>false ,'commune'=> $c]);

                                                                        if (!$ab) {
                                                                            $rep =array(
                                                                            'status' => false,  
                                                                              'data'=> '',
                                                                             'msg' => 'Invalide abonnement '

                                                                             );
                                                                        }else{
                                                                             

                                                                                

                                                                                $em=$this->getDoctrine()->getManager();
                                                                                $em->remove($ab);
                                                                                $em->flush();

                                                                                $rep=array(
                                                                                    'status'=> true,
                                                                                    'data'=>'',
                                                                                    'msg'=>'Desabonner'
                                                                                    );
                                                                        }

                                                }
        }

        $response = $serializer->serialize($rep, 'json');
        return new Response($response);



    
}
}

