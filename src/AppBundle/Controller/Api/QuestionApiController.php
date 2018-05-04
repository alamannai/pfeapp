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
use AppBundle\Entity\Question;
use AppBundle\Entity\Liste;
use AppBundle\Form\ListeType;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\JsonSerializable;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


    /**
     * @Route("api/question")
     */

class QuestionApiController extends Controller
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
        $questionfield=$request->request->get('question');
        $commune=$request->request->get('commune');

        if ($token) {


        $em = $this->getDoctrine()->getManager();
        $log = $em->getRepository('AppBundle:Token')->findOneBy([ 'tokenfield'=>$token]);

        $e = $this->getDoctrine()->getManager();
        $comm = $e->getRepository('AppBundle:Commune')->find($commune);


        $citoyen=$log->getCitoyen();
        $emm = $this->getDoctrine()->getManager();
        $abs = $emm->getRepository('AppBundle:Liste')->findOneBy([ 'commune'=>$comm, 'citoyen'=>$citoyen,'blocked'=>false]);
        
          if ($abs && $questionfield && $comm) {
                              $question = new Question();
                              $timep=new \DateTime();
                              $question->setCreatedAt($timep);
                            $question->setQuestionfield($questionfield);
                            $question->setCommune($comm);
                            $question->setCitoyen($citoyen);
                            $question->setReponse(null);

                            $em->persist($question);
                            $em->flush();
                            $rep=array(
                                'status' => true ,
                                'data' => '',
                                'msg'=> 'Question envoyee'
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
        $ques = $emm->getRepository('AppBundle:Question')->findBy([ 'citoyen'=>$citoyen->getId()]);
                  if ($ques) {
                     foreach ($ques as $que ) {

                                if ($que->getReponse()) {
                                  $r=$que->getReponse()->getReponsefield();
                                }else{
                                  $r='En traitement';
                                }

                        $liste[]=array(
                          'id'=>$que->getId(),
                          'question'=>$que->getQuestionfield(),
                          'reponse'=> $r,
                          'createdat'=>$que->getCreatedAt(),
                        );
                      }
                      $rep=array(
                          'status'=>true,
                          'data'=> $liste,
                          'msg'=>'Vos questions'
                      );
                  }else{
                    $rep=array(
                          'status'=>false,
                          'data'=> '',
                          'msg'=>'Aucun question'
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
        $que = $emm->getRepository('AppBundle:Question')->findOneBy(['id'=>$id, 'citoyen'=>$citoyen->getId()]);
                  if ($que) {
                    

                                if ($que->getReponse()) {
                                  $r=$que->getReponse()->getReponsefield();
                                }else{
                                  $r='En traitement';
                                }

                        $liste=array(
                          'id'=>$que->getId(),
                          'question'=>$que->getQuestionfield(),
                          'reponse'=> $r,
                          'createdat'=>$que->getCreatedAt(),
                        );
                      
                      $rep=array(
                          'status'=>true,
                          'data'=> $liste,
                          'msg'=>'Votre question'
                      );
                  }else{
                    $rep=array(
                          'status'=>false,
                          'data'=> '',
                          'msg'=>'Aucun question'
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

    public function deleteQue(Request $request,$id)
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
                $c = $em->getRepository('AppBundle:Question')->findOneBy(['id'=>$id, 'reponse'=>null]);

                if (!$c) {
                    $rep =array(
                    'status' => false,  
                      'data'=> '',
                     'msg' => 'Invalid question '

                     );
                }else{
                     
                        
                        if ($c ->getCitoyen()->getId() == $log->getCitoyen()->getId()) {
                            $em=$this->getDoctrine()->getManager();
                                $em->remove($c);
                                $em->flush();

                                $rep =array(
                                    'status' => true,  
                                      'data'=> '',
                                     'msg' => 'question supprimee '

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

