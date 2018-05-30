<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Sondage;
use AppBundle\Entity\Liste;
use AppBundle\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Sondage controller.
 *
 * @Route("commune/sondages")
 */
class SondageController extends Controller
{
    /**
     * Lists all sondage entities.
     *
     * @Route("/", name="sondage_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $commune = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $sondages = $em->getRepository('AppBundle:Sondage')->findBy(['commune'=>$commune]);
        $nb=count($sondages);

        return $this->render('sondage/index.html.twig', array(
            'sondages' => $sondages,
            'nb'=>$nb
        ));
    }

    /**
     * Creates a new sondage entity.
     *
     * @Route("/new", name="sondage_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $commune = $this->getUser();
        $sondage = new Sondage();
        $form = $this->createForm('AppBundle\Form\SondageType', $sondage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $e = $this->getDoctrine()->getManager();
            $citoyens = $e->getRepository('AppBundle:Liste')->findBy(['commune'=>$commune, 'blocked'=> false]);

             foreach ($citoyens as $citoyen) {
                $cit=$citoyen->getCitoyen();
                $com=$citoyen->getCommune();

                $notif= new Notification();
                $notif->setContenu('Nouveau Sondage');
                $time=new \DateTime(); 
                $notif->setCitoyen($cit);
                $notif->setCommune($com);
                $notif->setVue(false);
                $notif->setCreatedAt($time);


                $e = $this->getDoctrine()->getManager();
                $e->persist($notif);
                $e->flush();

            }
            $sondage->setTermine(false);
            $sondage->setCommune($com);

            $em = $this->getDoctrine()->getManager();
            $em->persist($sondage);
            $em->flush();

            return $this->redirectToRoute('sondage_show', array('id' => $sondage->getId()));
        }

        return $this->render('sondage/new.html.twig', array(
            'sondage' => $sondage,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a sondage entity.
     *
     * @Route("/{id}", name="sondage_show")
     * @Method("GET")
     */
    public function showAction(Sondage $sondage)
    {
        $deleteForm = $this->createDeleteForm($sondage);

        $e = $this->getDoctrine()->getManager();
        if ($limite = $e->getRepository('AppBundle:LimiteSondage')->findOneBy(['sondage'=>$sondage])) {
            $limite = $e->getRepository('AppBundle:LimiteSondage')->findOneBy(['sondage'=>$sondage]);
            $l=$limite->getFin();
        }else{
            $l='';
        }
        

        return $this->render('sondage/show.html.twig', array(
            'sondage' => $sondage,
            'limite'=>$l,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing sondage entity.
     *
     * @Route("/{id}/edit", name="sondage_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Sondage $sondage)
    {
        $deleteForm = $this->createDeleteForm($sondage);
        $editForm = $this->createForm('AppBundle\Form\SondageType', $sondage);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sondage_edit', array('id' => $sondage->getId()));
        }

        return $this->render('sondage/edit.html.twig', array(
            'sondage' => $sondage,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a sondage entity.
     *
     * @Route("/{id}", name="sondage_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Sondage $sondage)
    {
        $form = $this->createDeleteForm($sondage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sondage);
            $em->flush();
        }

        return $this->redirectToRoute('sondage_index');
    }

    /**
     * Creates a form to delete a sondage entity.
     *
     * @param Sondage $sondage The sondage entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Sondage $sondage)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sondage_delete', array('id' => $sondage->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
