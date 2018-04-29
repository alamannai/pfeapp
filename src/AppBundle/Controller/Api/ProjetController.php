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

        $encoders = array( new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        
        if ($commune){
            $em = $this->getDoctrine()->getManager();
            $projets = $em->getRepository('AppBundle:Projet')->findBy(['commune' => $commune]);

            if ($projets) {

                foreach ($projets as $projet) {
                    $em = $this->getDoctrine()->getManager();
                    $e = $em->getRepository('AppBundle:EtatProjet')->findOneBy( [ 'projet'=>$projet->getId()]);

                if ($projet->getDone()== null) {
                        $msg='En cours de realisation';
                }else {
                        if ( $projet->getDone() == false) {

                            $msg=$e->getReason();
                        }else{

                            $msg='Projet termine';
                    }
                }
                    $list[]=array(
                        'id' =>$projet->getId(),
                        'sujet'=>$projet->getSujet(),
                        'contenu'=>$projet->getContenu(),
                        'datedebut'=>$projet->getDateDebut(),
                        'duree'=>$projet->getDuree(),
                        'votes'=>count($projet->getVotes()),
                        'commentaires'=>$projet->getCommentaires(),
                        'etat'=>$msg
                    );

                   
                }

                $listepro= array(
                    'status' => true ,
                    'data' => $list,
                    'msg' => ' La liste des projets'


                );
            
            
            }else{
            $listepro=array(
                'status' => true ,
                'data' => '',
                'msg' => 'Aucun projet'

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

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        
        if ($commune){

        $em=$this->getDoctrine()->getManager();
        $projet=$em->getRepository('AppBundle:Projet')->findOneBy(['commune'=> $commune, 'id'=> $id]);

        

        if ($projet) {

            $em = $this->getDoctrine()->getManager();
            $e = $em->getRepository('AppBundle:EtatProjet')->findOneBy( [ 'projet'=>$projet->getId()]);

                if ($projet->getDone()== null) {
                        $msg='En cours de realisation';
                }else {
                        if ($projet->getDone() == false) {

                            $msg=$e->getReason();
                        }else{

                            $msg='Projet termine';
                    }
                }

                if ($projet->getImage()) {
                    $img=$projet->getImage();
                }else{
                    $img="aucune image" ;
                }
                    $li=array(
                        'id' =>$projet->getId(),
                        'sujet'=>$projet->getSujet(),
                        'contenu'=>$projet->getContenu(),
                        'datedebut'=>$projet->getDateDebut(),
                        'duree'=>$projet->getDuree(),
                        'votes'=>count($projet->getVotes()),
                        'commentaires'=>$projet->getCommentaires(),
                        'Etat'=>$msg,
                        'image'=>$img
                    );

            $listepro= array(
                    'status' => true ,
                    'data' => $li,
                    'msg' => ' Le projet'


                );
        }else{
            $listepro=array(
                'status' => false ,
                'data' => '',
                'msg' => 'Aucun projet'

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
