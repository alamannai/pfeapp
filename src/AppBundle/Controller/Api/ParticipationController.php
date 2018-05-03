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
use AppBundle\Entity\Participation;
use AppBundle\Entity\Citoyen;
use AppBundle\Entity\Commune;
use AppBundle\Entity\Sondage;
use AppBundle\Form\ParticipationType;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\JsonSerializable;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use AppBundle\Security\TokenAuthenticator;

  /**
     * @Route("api/commune/{commune}/sondages/{sondage}/participation")
     * 
     */  


class ParticipationController extends Controller
{
   
 

  /**
     * @Route("/new", name="new_participation")
     * @Method("POST")
     */
    public function newAction(Request $request,$sondage,$commune)
    {

        $encoders = array( new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $token=$request->request->get('token') ;
        $type=$request->request->get('participation') ;

        if (!$token && !$type) {
            $rep =array(
              'status' => false,  
              'data'=> '',
             'msg' => 'Invalide parametre'

             );
        }else{
            $em = $this->getDoctrine()->getManager();
        $log = $em->getRepository('AppBundle:Token')->findOneBy([ 'tokenfield'=>$token]);

        if ($log) {
            $citoyen=$log->getCitoyen();
    
        $em = $this->getDoctrine()->getManager();
        $s = $em->getRepository('AppBundle:Sondage')->find( $sondage);

       

        if ($s!=null) {
        $c=$s->getCommune();
        
        
        

        if ($c==$commune) {

          $timep=new \DateTime();
        $participation = new Participation();
        $participation->setCitoyen($citoyen);
        $participation->setSondage($s);

        $participation->setType($type);
        $participation->setDatep($timep);

        $form = $this->createForm(ParticipationType::class, $participation);
        $form->handleRequest($request);
 
        
        $em = $this->getDoctrine()->getManager();
        $parti = $em->getRepository('AppBundle:Participation')->findBy(['sondage' => $sondage , 'citoyen' => $citoyen->getId()]);
        $dones=$s->getTermine();

        $e = $this->getDoctrine()->getManager();
        $i = $em->getRepository('AppBundle:LimiteSondage')->findOneBy(['sondage' => $sondage ]);
        $timef=$i->getFin();

                              if (!$parti && $dones==false && $timef>=$timep) {

                                  $em->persist($participation);
                                  $em->flush();

                                  $rep =array(
                                    'status' => true,  
                                    'data'=> '',
                                   'msg' => 'Participation'

                                   );     
                                      }else{
                                          $rep =array(
                                                'status' => false,  
                                                'data'=> '',
                                               'msg' => 'pas de Participation'

                                               );
                                          }
                }
            }
        }else{
            $rep =array(
                          'status' => false,  
                          'data'=> '',
                         'msg' => 'Invalide token'

                         );
        }
        
        }

        
        
       

           

        $response = $serializer->serialize($rep, 'json');
        return new Response($response);
        
    }




}
