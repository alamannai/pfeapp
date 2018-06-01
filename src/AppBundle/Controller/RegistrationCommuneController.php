<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Commune;
use AppBundle\Form\CommuneType;

use AppBundle\Entity\Gouvernorat;
use AppBundle\Form\GouvernoratType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegistrationCommuneController extends Controller
{







    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {   



        $em = $this->getDoctrine()->getManager();
        $governorats = $em->getRepository('AppBundle:Gouvernorat')->findall();


        


        return $this->render('admin/register.html.twig', [
            'gouvernorats'=> $governorats

        ]);
    }



    /**
     * @Route("/register/add", name="add")
     */
    public function addAction(Request $request)
    {   

           $nom=$request->request->get('nom');  
                  $pseudo=$request->request->get('pseudo');   
                  $password=$request->request->get('password');
                   $gov=$request->request->get('gouvernorat');

        $commune = new Commune();



                    
                $e = $this->getDoctrine()->getManager();
        $gouvernorat = $e->getRepository('AppBundle:Gouvernorat')->findOneBy(['nom'=> $gov]);
       


            // Encode the new users password
            $encoder = $this->get('security.password_encoder');
            $password = $encoder->encodePassword($commune, $commune->getPlainPassword());
            $commune->setPassword($password);

            // Set their role
            $commune->setRole('ROLE_COMMUNE');

  $commune->setNom($nom);
    $commune->setPseudo($pseudo);

            
            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($commune);
            $em->flush();

      return $this->redirectToRoute('admin');
    }
}