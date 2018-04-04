<?php

namespace AppBundle\Controller\Api;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use AppBundle\Entity\Citoyen;
use AppBundle\Form\CitoyenType;
use Symfony\Component\Form\FormInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Projet;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\JsonSerializable;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class RegistrationController extends Controller
{
    
  /**
     * @Route("/api/register", name="user_registration")
     * @Method("POST")
     */
    public function registerAction(Request $request)
    {

        $encoders = array( new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);


        $nom=$request->query->get('nom');
        $prenom=$request->query->get('prenom');
        $email=$request->query->get('email');
        $password=$request->query->get('password');


    
        $citoyen = new Citoyen();
        $citoyen->setNom($nom);
        $citoyen->setPrenom($prenom);
        $citoyen->setEmail($email);

        $form = $this->createForm(CitoyenType::class, $citoyen);
        $form->handleRequest($request);
 
        $encoder = $this->get('security.password_encoder');
        $password = $encoder->encodePassword($citoyen, $citoyen->getPlainPassword());
            

        $citoyen->setRole('ROLE_CITOYEN');
        $citoyen->setPassword($password);
           
        $em = $this->getDoctrine()->getManager();
        $emailcheck = $em->getRepository('AppBundle:Citoyen')->findBy(['email' => $email]);

        if ($emailcheck== null) {

            $em->persist($citoyen);
            $em->flush();

            $rep =array(
             'msg' => 'compte cree'
             );
        }else{
            $rep =null;
        }
        
       


           

        $response = $serializer->serialize($rep, 'json');
        return new Response($response);
        
    }

    



}
