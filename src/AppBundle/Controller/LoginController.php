<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use AppBundle\Entity\Citoyen;
use AppBundle\Entity\Token;
use AppBundle\Repository\citoyenRepository;
use AppBundle\Form\CitoyenType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\JsonSerializable;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use  Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;



class LoginController extends Controller
{
    /**
     * @Route("/api/login", name="user_login")
     * @Method("POST")
     */
    public function loginAction(Request $request)
    {
        $encoders = array( new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $email = $request->request->get('email');
        $password = $request->request->get('password');

    

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:Citoyen')->findOneBy(['email' => $email]);

        
       

        if (!$user) {
         
            $rep=array(
            'status'=>false,
            'data' => '',
            'msg'=> "verifier l'email"
        );
        }else{
            $encoder = $this->get('security.password_encoder');
            $isValid=$encoder->isPasswordValid(
            $user, // the encoded password
            $password,       // the submitted password
            $user->getSalt()
        );

            if (!$isValid) {
         
            
            $rep=array(
            'status'=>false,
            'data' => '',
            'msg'=> 'verifier le mot de passe'
        );
        }else{
                    $em = $this->getDoctrine()->getManager();
                    $checktoken = $em->getRepository('AppBundle:Token')->findOneBy(['citoyen' => $user->getId()]);
                        if ($checktoken) {
                            $token=$checktoken->getTokenfield();
                        }else{
                            $token = $this->getToken($user);
                        
                                $t= new token();
                                $t->setTokenfield($token);
                                $t->setCitoyen($user);
                                

                                $em=$this->getDoctrine()->getManager();
                                $em->persist($t);
                                $em->flush();

                        }

                        


                        $rep=array(
                        'status'=>true,
                        'data' => $token,
                        'msg'=> 'welcome'
                    );
        }

        }

        

        

        

        

        $response = new Response($serializer->serialize($rep, 'json'));
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');


        return $response;
    }

    /**
     * Returns token for user.
     *
     * @param Citoyen $user
     *
     * @return array
     */
    public function getToken(Citoyen $user)
    {
        return $this->container->get('lexik_jwt_authentication.encoder')
                ->encode([
                    'email' => $user->getEmail(),
                    'exp' => $this->getTokenExpiryDateTime(),
                ]);
    }

    /**
     * Returns token expiration datetime.
     *
     * @return string Unixtmestamp
     */
    private function getTokenExpiryDateTime()
    {
        $tokenTtl = $this->container->getParameter('lexik_jwt_authentication.token_ttl');
        $now = new \DateTime();
        $now->add(new \DateInterval('PT'.$tokenTtl.'S'));

        return $now->format('U');
    }
}
