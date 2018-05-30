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
use AppBundle\Entity\Reclamation;
use AppBundle\Entity\Liste;
use AppBundle\Form\ListeType;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\JsonSerializable;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


    /**
     * @Route("api/reclamation")
     */

class ReclamationApiController extends Controller
{

 /**
     * @Route("/new")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {

        $encoders = array( new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);


        $token=$request->request->get('token') ;
        $contenu=$request->request->get('contenu');
        $commune=$request->request->get('commune');

        if ($token) {


        $em = $this->getDoctrine()->getManager();
        $log = $em->getRepository('AppBundle:Token')->findOneBy([ 'tokenfield'=>$token]);

        $e = $this->getDoctrine()->getManager();
        $comm = $e->getRepository('AppBundle:Commune')->find($commune);


        $citoyen=$log->getCitoyen();
        $emm = $this->getDoctrine()->getManager();
        $abs = $emm->getRepository('AppBundle:Liste')->findOneBy([ 'commune'=>$comm, 'citoyen'=>$citoyen,'blocked'=>false]);
        
          if ($abs && $contenu && $comm) {
                              $reclamation = new Reclamation();
                              $timep=new \DateTime();
                              $reclamation->setCreatedAt($timep);
                            $reclamation->setContenu($contenu);
                            $reclamation->setCommune($comm);
                            $reclamation->setCitoyen($citoyen);
                            $reclamation->setClosed(null);

                            if ((!empty($request->request->get('lat')) && !empty($request->request->get('lng'))) ) {
                                       $lat=$request->request->get('lat');
                                       $lng=$request->request->get('lng');
                                      $reclamation->setLat($lat);
                                      $reclamation->setLng($lng);
                                     
                            }

                            if (!empty($request->request->get('image'))) {
                               $image = $request->request->get('image');
                                         
                                         $reclamation->setImage($image);
                            }

                            $em->persist($reclamation);
                            $em->flush();
                            $rep=array(
                                'status' => true ,
                                'data' => '',
                                'msg'=> 'Reclamation envoyee'
                              );
                  }else{
                    $rep=array(
                                        'status' => false ,
                                        'data' => '',
                                        'msg'=> 'Verifier abonnement et parametre'
                                      );
                  }
          
           
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
        $recs = $emm->getRepository('AppBundle:Reclamation')->findBy([ 'citoyen'=>$citoyen->getId()]);
                  if ($recs) {
                     foreach ($recs as $rec ) {

                                if ($rec->getClosed()=== true) {
                                  $et='resolue';
                                }elseif($rec->getClosed()=== null){
                                  $et='En attente';
                                }else{
                                  
                                  $et='En traitement';
                                }

                        $liste[]=array(
                          'id'=>$rec->getId(),
                          'contenu'=>$rec->getContenu(),
                          'etat'=> $et,
                          'image'=> $rec->getImage(),
                          'lat'=> $rec->getLat(),
                          'lng'=>$rec->getLng()
                        );
                      }
                      $rep=array(
                          'status'=>true,
                          'data'=> $liste,
                          'msg'=>'Vos reclamations'
                      );
                  }else{
                    $rep=array(
                          'status'=>true,
                          'data'=> '',
                          'msg'=>'Aucune reclamation'
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



  /**
     * @Route("/{id}")
     * @Method("GET")
     */
    public function showAction(Request $request,$id)
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
        $rec = $emm->getRepository('AppBundle:Reclamation')->find($id);
                  if ($rec) {
                    

                                if ($rec->getClosed()== true) {
                                  $et='resolue';
                                }elseif($rec->getClosed()== null){
                                  $et='En attente';
                                }else{
                                  
                                  $et='En traitement';
                                }

                        $liste=array(
                          'id'=>$rec->getId(),
                          'contenu'=>$rec->getContenu(),
                          'etat'=> $et,
                          'image'=> $rec->getImage(),
                          'lat'=> $rec->getLat(),
                          'lng'=>$rec->getLng()
                        );
                      
                      $rep=array(
                          'status'=>true,
                          'data'=> $liste,
                          'msg'=>'Votre reclamation'
                      );
                  }else{
                    $rep=array(
                          'status'=>true,
                          'data'=> '',
                          'msg'=>'Invalide reclamation'
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



    /**
     * @Route("/{id}")
     * @Method("DELETE")
     */

    public function deleteRec(Request $request,$id)
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

        if (!$log) {
            $rep =array(
              'status' => false,  
              'data'=> '',
             'msg' => 'Invalide token '

             );
        }else{

                    $em = $this->getDoctrine()->getManager();
                $c = $em->getRepository('AppBundle:Reclamation')->findOneBy(['id'=>$id, 'closed'=>null]);

                if (!$c) {
                    $rep =array(
                    'status' => false,  
                      'data'=> '',
                     'msg' => 'Invalide reclamation '

                     );
                }else{
                     
                        
                        if ($c ->getCitoyen()->getId() == $log->getCitoyen()->getId()) {
                            $em=$this->getDoctrine()->getManager();
                                $em->remove($c);
                                $em->flush();

                                $rep =array(
                                    'status' => true,  
                                      'data'=> '',
                                     'msg' => 'reclamation supprimee '

                                     );
                        }else{
                            $rep =array(
                                'status' => false,  
                                  'data'=> '',
                                 'msg' => 'non autorisee '

                                 );
                        }

                       
                        
                        

                        

                        

                        }
                        
                        
                        
                    

                        
                        
                        

                }

       
        }

        $response = $serializer->serialize($rep, 'json');
        return new Response($response);



    }
}

