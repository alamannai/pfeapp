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

        // use request to read post params 
        // query is for get request 
        
        $nom=$request->request->get('nom');
        $prenom=$request->request->get('prenom');
        $email=$request->request->get('email');
        $password=$request->request->get('password');

        // add param control 
        if($nom && $prenom && $email && $password){
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
                $rep =array('ok' => false, 'msg' => 'already existe');
            }else{
                $rep =array(
                    'ok' => false,
                    'msg' => 'already existe'
                    );
            }    
        }
        else{
            $rep =array(
                'ok' => false,
                'msg' => 'param missing'
                );
        }
        $response = $serializer->serialize($rep, 'json');
        return new Response($response);
        
    }

    



}
