<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use AppBundle\Entity\Citoyen;
use AppBundle\Form\CitoyenType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\JsonSerializable;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use  Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use AppBundle\Helper\ControllerHelper;

class LoginController extends Controller
{

   /**
     * @Route("/api/login", name="login")
     * @Method("POST")
     */
    public function loginAction(Request $request)
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $email = $request->request->get('email');
        $password = $request->request->get('password');

        $em = $this->getDoctrine()->getManager();
        $citoyen = $em->getRepository('AppBundle:Citoyen')->findOneBy(['email' => $email]);

        if ($citoyen) {

             

           $encode = $this->get('security.password_encoder');
            $pass=$encode->isPasswordValid($citoyen, $password);

            

        if ($pass) {
            
            $token = $this->getToken($citoyen);
            $rep=array(
                'status' => true,
                'data' => $token,
                'msg' => 'authentifie reussi '
            );
        

        }else{
            $rep=array(
                'status' => false,
                'data' => '',
                'msg' => 'verifier le mot de passe'
            );
        }
    }
           
        else{
            $rep=array(
                'status' => false,
                'data' => '',
                'msg' => 'verifier votre mail '
            );
        }

        
        
       
        $response = $serializer->serialize($rep, 'json');

        return new Response($response);
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
