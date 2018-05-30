<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Citoyen;
use AppBundle\Entity\Liste;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Citoyen controller.
 *
 * @Route("citoyens")
 */
class CitoyenController extends Controller
{
    /**
     * Lists all citoyen entities.
     *
     * @Route("/", name="citoyens_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $commune = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $liste = $em->getRepository('AppBundle:Liste')->findBy(['commune'=>$commune],['blocked' => 'ASC']);

        return $this->render('espaceCommune/index.html.twig', array(
            'liste' => $liste,
        ));
    }


    /**
     * Finds and displays a projet entity.
     *
     * @Route("/blocked",name="blocked")
     */
    public function blockedAction(Request $request)  
     {
        $commune = $this->getUser();
        $idc=$request->query->get('id');

        $em = $this->getDoctrine()->getManager();
        $ab = $em->getRepository('AppBundle:Liste')->findOneBy(['citoyen'=> $idc , 'commune'=>$commune]);

        $ab->setBlocked(true);

       $em->persist($ab);
        $em->flush();
        

        return $this->redirectToRoute('citoyens_index');
    }

    /**
     * Finds and displays a projet entity.
     *
     * @Route("/deblocked",name="deblocked")
     */
    public function deblockedAction(Request $request)  
     {
        $commune = $this->getUser();
        $idc=$request->query->get('id');

        $em = $this->getDoctrine()->getManager();
        $ab = $em->getRepository('AppBundle:Liste')->findOneBy(['citoyen'=> $idc , 'commune'=>$commune]);

        $ab->setBlocked(false);

       $em->persist($ab);
        $em->flush();
        

        return $this->redirectToRoute('citoyens_index');
    }

}
