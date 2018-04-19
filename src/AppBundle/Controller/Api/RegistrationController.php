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


        $nom=$request->request->get('nom');
        $prenom=$request->request->get('prenom');
        $email=$request->request->get('email');
        $password1=$request->request->get('password');

        if ($nom && $prenom && $email && $password1) {
            
        $citoyen = new Citoyen();
        $citoyen->setNom($nom);
        $citoyen->setPrenom($prenom);
        $citoyen->setEmail($email);
        $citoyen->setUsername($email);
        $citoyen->setPlainPassword($password1);

        $form = $this->createForm(CitoyenType::class, $citoyen);
        $form->handleRequest($request);
 
        $encoder = $this->get('security.password_encoder');
        $password = $encoder->encodePassword($citoyen, $citoyen->getPlainPassword());

            

        $citoyen->setRoles(['ROLE_CITOYEN']);
        $citoyen->setPassword($password);

        $citoyen->setEnabled(true);
 
           
        $em = $this->getDoctrine()->getManager();
        $emailcheck = $em->getRepository('AppBundle:Citoyen')->findBy(['email' => $email]);

        if ($emailcheck== null) {

            $em->persist($citoyen);
            $em->flush();

            $rep =array(
              'status' => true,  
              'data'=> $email,
             'msg' => 'compte cree'

             );
        }else{
            $rep =array(
                'status'=> false ,
                'data' => $email,
                'msg' => 'email utilise'
            );
        }
        
       
        }else{
            $rep =array(
              'status' => false,  
              'data'=> '',
             'msg' => 'verifier les champs'

             );
        }
    


           

        $response = $serializer->serialize($rep, 'json');
        return new Response($response);
        
    }

    



}
