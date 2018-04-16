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
use AppBundle\Entity\Commentaire;
use AppBundle\Entity\Projet;
use AppBundle\Entity\Citoyen;
use AppBundle\Form\CommentaireType;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\JsonSerializable;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

    /**
     * @Route("api/commune/{commune}/projets/{projet}/commentaires")
     * 
     */  


class CommentaireController extends Controller
{
    /**
     * @Route("/")
     * @Method("GET")
     */
    public function listAction(Request $request,$projet,$commune)
    {

        $liste=null;

        $encoders = array( new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $em = $this->getDoctrine()->getManager();
        $p = $em->getRepository('AppBundle:Projet')->find( $projet);

        if ($p!=null) {
        $c=$p->getCommune();
        

        

        if ($c==$commune) {

        
        $em = $this->getDoctrine()->getManager();
        $commentaires = $em->getRepository('AppBundle:Commentaire')->findBy(['projet' => $projet]);

        foreach ($commentaires as $commentaire ) {

            if ($commentaire->getValidation()==true) {

                $nom=$commentaire->getCitoyen()->getNom();
                $prenom=$commentaire->getCitoyen()->getPrenom();
                $np=$prenom.' '.$nom;
                $liste[]=array(
                    'id'=>$commentaire->getId(),
                    'commentaire'=>$commentaire->getContenu() ,
                    'Citoyen_id' => $commentaire->getCitoyen()->getId(),
                    'Citoyen'=> $np
                );
            }
        
        }
    }
}
        
        $response = $serializer->serialize($liste, 'json');
         
        
        return new Response($response);
    
    }

 

    /**
     * @Route("/new")
     * @Method("POST")
     */
    public function newAction(Request $request,$projet,$commune)
    {

        $encoders = array( new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);


        $citoyen_id=$request->request->get('citoyen');
        $contenu=$request->request->get('contenu');
        
    
        $em = $this->getDoctrine()->getManager();
        $p = $em->getRepository('AppBundle:Projet')->find( $projet);

        $emm = $this->getDoctrine()->getManager();
        $citoyen = $emm->getRepository('AppBundle:Citoyen')->find($citoyen_id);

        if ($p!=null) {
        $c=$p->getCommune();
        
        $rep=null;
        

        if ($c==$commune) {
        $commentaire = new Commentaire();
        $commentaire->setCitoyen($citoyen);
        $commentaire->setProjet($p);
        $commentaire->setContenu($contenu);
        $commentaire->setValidation(false);
        

        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        $em->persist($commentaire);
        $em->flush();

            $rep =true;
        
         }
        }
        
       


           

        $response = $serializer->serialize($rep, 'json');
        return new Response($response);
        
    }




}
