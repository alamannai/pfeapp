<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Projet;
use AppBundle\Entity\Liste;
use AppBundle\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Projet controller.
 *
 * @Route("projet")
 */
class ProjetController extends Controller
{
    /**
     * Lists all projet entities.
     *
     * @Route("/", name="projet_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $nb=0;

        $projets = $em->getRepository('AppBundle:Projet')->findAll();


        
        

        return $this->render('projet/index.html.twig', array(
            'projets' => $projets,
            'nb'=>count($projets)
            ));
    }

    /**
     * Creates a new projet entity.
     *
     * @Route("/new", name="projet_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $projet = new Projet();
        $form = $this->createForm('AppBundle\Form\ProjetType', $projet);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $projets = $em->getRepository('AppBundle:Projet')->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
             $file = $projet->getImage();
             $unique = md5($file. time());

            $fileName = $unique.'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('image_directory'),
                $fileName
            );
            $e = $this->getDoctrine()->getManager();
            $citoyens = $e->getRepository('AppBundle:Liste')->findBy(['commune'=>'9', 'blocked'=> false]);

             foreach ($citoyens as $citoyen) {
                $cit=$citoyen->getCitoyen();
                $com=$citoyen->getCommune();

                $notif= new Notification();
                $notif->setContenu('Nouveau Projet');
                $time=new \DateTime(); 
                $notif->setCitoyen($cit);
                $notif->setCommune($com);
                $notif->setVue(false);
                $notif->setCreatedAt($time);


                $e = $this->getDoctrine()->getManager();
                $e->persist($notif);
                $e->flush();

            }
            

            $projet->setImage($fileName);
            $projet->setCommune($com);


                       


            $em = $this->getDoctrine()->getManager();
            $em->persist($projet);
            $em->flush();


            
            
           

            return $this->redirectToRoute('projet_show', array('id' => $projet->getId()));
        }

        return $this->render('projet/new.html.twig', array(
            'projet' => $projet,
            'votes'=> count($projet->getVotes()),
            'commentaires'=>$projet->getAllommentaires(),
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a projet entity.
     *
     * @Route("/{id}", name="projet_show")
     * @Method("GET")
     */
    public function showAction(Projet $projet)
    {
        $deleteForm = $this->createDeleteForm($projet);
      


        return $this->render('projet/show.html.twig', array(
            'projet' => $projet,
            'npvotes'=>$projet->getVotes(),
            'votes'=> count($projet->getVotes()),
            'commentaires'=>$projet->getAllommentaires(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing projet entity.
     *
     * @Route("/{id}/edit", name="projet_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Projet $projet)
    {
        $deleteForm = $this->createDeleteForm($projet);
        $editForm = $this->createForm('AppBundle\Form\ProjetType', $projet);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $imageFile = $editForm->get('image')->getData();
        if (null != $imageFile) { 

            $file = $projet->getImage();
             $unique = md5($file. time());

            $fileName = $unique.'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('image_directory'),
                $fileName
            );

            $projet->setImage($fileName);
            $this->getDoctrine()->getManager()->flush();

            //4. add a success flash, and anything else you need, and redirect to a route
        } else { //if the user has chosen to edit a different field (but not the image one)
            $this->getDoctrine()->getManager()->flush();
            
        }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('projet_edit', array('id' => $projet->getId()));
        }

        return $this->render('projet/edit.html.twig', array(
            'projet' => $projet,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a projet entity.
     *
     * @Route("/{id}", name="projet_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Projet $projet)
    {
        $form = $this->createDeleteForm($projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($projet);
            $em->flush();
        }

        return $this->redirectToRoute('projet_index');
    }

    /**
     * Creates a form to delete a projet entity.
     *
     * @param Projet $projet The projet entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Projet $projet)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('projet_delete', array('id' => $projet->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
