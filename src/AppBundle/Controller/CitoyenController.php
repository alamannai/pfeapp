<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Citoyen;
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
        $em = $this->getDoctrine()->getManager();

        $liste = $em->getRepository('AppBundle:Liste')->findBy(['commune'=>'9'],['blocked' => 'ASC']);

        return $this->render('espaceCommune/index.html.twig', array(
            'liste' => $liste,
        ));
    }

}
