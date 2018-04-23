<?php


namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Citoyen;
use AppBundle\Entity\Liste;
use AppBundle\Entity\Token;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\JsonSerializable;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * 
 *
 * @Route("api/profil")
 */
class ProfilController extends Controller
{
     /**
     *
     * @Route("/")
     * @Method("GET")
     */



    public function showAction(Request $request)
    {
    	$encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $token=$request->query->get('token');

        if (!$token) {
            $rep =array(
              'status' => false,  
              'data'=> '',
             'msg' => 'Pas de connexion '

             );
        }else{
            $em = $this->getDoctrine()->getManager();
            $log = $em->getRepository('AppBundle:Token')->findOneBy([ 'tokenfield'=>$token]);
                if ($log) {
                    $citoyen=$log->getCitoyen();

           
                        $em = $this->getDoctrine()->getManager();
                            $abs = $em->getRepository('AppBundle:Liste')->findBy([ 'citoyen'=>$citoyen,'blocked'=>false]);
                                    if ($abs) {
                                       foreach ($abs as $ab ) {
                                        $c=array(
                                                'id'=>$ab->getCommune()->getId(),
                                                'nom'=>$ab->getCommune()->getNom()
                                            );
                                            $liste[]=$c;
                                    }
                                    
                            }

            if (empty($liste)) {
                $liste='Aucune commune';
            }
            
            $c=array(
                'id'=>$citoyen->getId(),
                'nom'=>$citoyen->getNom(),
                'prenom'=>$citoyen->getPrenom(),
                'email'=>$citoyen->getEmail(),
                'communes'=>$liste
            );

           $rep=array(
            'status'=> true,
            'data'=>$c,
            'msg'=> 'Profil'
           );
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
    

    /**
     *
     * @Route("/update")
     * @Method("POST")
     */



    public function updateAction(Request $request)
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $token=$request->request->get('token');
        $nom=$request->request->get('nom');
        $prenom=$request->request->get('prenom');
        $email=$request->request->get('email');

        if (!$token) {
            $rep =array(
              'status' => false,  
              'data'=> '',
             'msg' => 'Pas de connexion '

             );
        }else{
            if ($nom && $prenom && $email) {
                $em = $this->getDoctrine()->getManager();
            $log = $em->getRepository('AppBundle:Token')->findOneBy([ 'tokenfield'=>$token]);

            $em = $this->getDoctrine()->getManager();
            $mail = $em->getRepository('AppBundle:Citoyen')->findOneBy([ 'email'=>$email]);
                         if ($log && !$mail) {
                        $citoyen=$log->getCitoyen();

                        $citoyen->setNom($nom);
                        $citoyen->setPrenom($prenom);
                        $citoyen->setEmail($email);
                        $citoyen->setUsername($email);

                        $em=$this->getDoctrine()->getManager();
                        $em->persist($citoyen);
                        $em->flush();

                       $rep=array(
                        'status'=> true,
                        'data'=>'',
                        'msg'=> 'Profil modifie'
                       );
                        }else{
                        $rep =array(
                                      'status' => false,  
                                      'data'=> '',
                                     'msg' => 'check token et email'

                                     );
                        }
            }else{
                $rep =array(
                                      'status' => false,  
                                      'data'=> '',
                                     'msg' => 'Invalid parametres'

                                     );
            }
            
        }
        
        
        
        $response = $serializer->serialize($rep, 'json');
        return new Response($response);
    }







    /**
     * @Route("/{id}")
     * @Method("DELETE")
     */

    public function deleteProfil(Request $request,$id)
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
                $c = $em->getRepository('AppBundle:Citoyen')->find($id);

                if (!$c) {
                    $rep =array(
                    'status' => false,  
                      'data'=> '',
                     'msg' => 'Invalide compte '

                     );
                }else{
                     $em = $this->getDoctrine()->getManager();
                        $votes = $em->getRepository('AppBundle:Vote')->findBy(['citoyen'=>$id]);
                        
                        

                        $em = $this->getDoctrine()->getManager();
                        $coms = $em->getRepository('AppBundle:Commentaire')->findBy(['citoyen'=>$id]);
                        
                        

                        

                        $em=$this->getDoctrine()->getManager();
                        $em->remove($log);
                        $em->flush();

                        if ($votes) {
                           foreach ($votes as $vote ) { 
                        $em->remove($vote);
                            }
                        $em->flush();
                        
                        }
                        
                        if ($coms) {
                            foreach ($coms as $com ) {
                          $em->remove($com);
                        }
                        $em->flush();
                        }
                        }
                        
                        
                        
                    

                        
                        
                        

                        $em=$this->getDoctrine()->getManager();
                        $em->remove($c);


                        $em->flush();

                        $rep=array(
                            'status'=> true,
                            'data'=>'',
                            'msg'=>'Compte supprime !!'
                            );
                }

       
        }

        $response = $serializer->serialize($rep, 'json');
        return new Response($response);



    }





    /**
     *
     * @Route("/password/update")
     * @Method("POST")
     */



    public function updatePassAction(Request $request)
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $token=$request->request->get('token');
        $old=$request->request->get('old');
        $new=$request->request->get('new');
    

        if (!$token) {
            $rep =array(
              'status' => false,  
              'data'=> '',
             'msg' => 'Pas de connexion '

             );
        }else{
            if ($old && $new ) {
                $em = $this->getDoctrine()->getManager();
            $log = $em->getRepository('AppBundle:Token')->findOneBy([ 'tokenfield'=>$token]);

          
            
                         if ($log) {
                          $citoyen=$log->getCitoyen();

                          $encoder = $this->get('security.password_encoder');
                          $isValid=$encoder->isPasswordValid(
                          $citoyen, 
                          $old,      
                          $citoyen->getSalt()
                      );
                           if (!$isValid) {
         
            
                                  $rep=array(
                                  'status'=>false,
                                  'data' => '',
                                  'msg'=> 'verifier le mot de passe'
                                 );
                              }else{
                                $citoyen->setPlainPassword($new);

                                
                         
                                $encoder = $this->get('security.password_encoder');
                                $password = $encoder->encodePassword($citoyen, $citoyen->getPlainPassword());

                                    

                                $citoyen->setPassword($password);

                                  $em=$this->getDoctrine()->getManager();
                                  $em->persist($citoyen);
                                  $em->flush();

                                 $rep=array(
                                  'status'=> true,
                                  'data'=>'',
                                  'msg'=> 'Mot de passe modifie'
                                 );
                              }
                        
                        }else{
                        $rep =array(
                                      'status' => false,  
                                      'data'=> '',
                                     'msg' => 'check token et mot de passe'

                                     );
                        }
            }else{
                $rep =array(
                                      'status' => false,  
                                      'data'=> '',
                                     'msg' => 'Invalid parametres'

                                     );
            }
            
        }
        
        
        
        $response = $serializer->serialize($rep, 'json');
        return new Response($response);
    }

}