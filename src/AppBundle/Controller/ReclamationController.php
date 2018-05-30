<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Reclamation;
use AppBundle\Entity\Commune;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Reclamation controller.
 *
 * @Route("citoyen/reclamations")
 */
class ReclamationController extends Controller
{
    /**
     * Lists all reclamation entities.
     *
     * @Route("/", name="reclamation_index")
     */
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();
        $reclamations = $em->getRepository('AppBundle:Reclamation')->findall();
           
    

        return $this->render('reclamation/index.html.twig', array(
            'reclamations' => $reclamations
        ));
    }

    /**
     * Finds and displays a reclamation entity.
     *
     * @Route("/{id}", name="reclamation_show")
     * @Method("GET")
     */
    public function showAction(Reclamation $reclamation)
    {
        $deleteForm = $this->createDeleteForm($reclamation);

        return $this->render('reclamation/show.html.twig', array(
            'reclamation' => $reclamation,
             'delete_form' => $deleteForm->createView(),
        ));
    }



     /**
     * Deletes a reclamation entity.
     *
     * @Route("/{id}", name="reclamation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Reclamation $reclamation)
    {
        $form = $this->createDeleteForm($reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            

            $em = $this->getDoctrine()->getManager();
            $em->remove($reclamation);
            $em->flush();
        }

        return $this->redirectToRoute('reclamation_index');
    }

    /**
     * Creates a form to delete a reclamation entity.
     *
     * @param Reclamation $reclamation The reclamation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Reclamation $reclamation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reclamation_delete', array('id' => $reclamation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }



    /**
     * Finds and displays a reclamation entity.
     *
     * @Route("/{id}/close",name="reclamation_etat")
     */
    public function updateEtatAction(Request $request, Reclamation $reclamation)    {

        $em = $this->getDoctrine()->getManager();
        $reclamation = $em->getRepository('AppBundle:Reclamation')->find($reclamation);

        $reclamation->setClosed(true);

       $em->persist($reclamation);
        $em->flush();
        

        return $this->redirectToRoute('reclamation_show', array('id' => $reclamation->getId()));
    }
}
