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
                            $reclamation->setClosed(false);

                            if ((!empty($request->request->get('lat')) && !empty($request->request->get('lng'))) ) {
                                       $lat=$request->request->get('lat');
                                       $lng=$request->request->get('lng');
                                      $reclamation->setLat($lat);
                                      $reclamation->setLng($lng);
                                     
                            }

                            if (!empty($request->files->get('image'))) {
                               $file = $request->files->get('image');
                                         $unique = md5($file. time());

                                        $fileName = $unique.'.'.$file->guessExtension();

                                        $file->move(
                                            $this->getParameter('imageRec_directory'),
                                            $fileName
                                        );
                                         $reclamation->setImage($fileName);
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

}

