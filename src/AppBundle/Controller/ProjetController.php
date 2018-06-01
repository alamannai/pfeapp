<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Projet;
use AppBundle\Entity\Liste;
use AppBundle\Entity\Notification;
use AppBundle\Entity\Commentaire;
use AppBundle\Entity\EtatProjet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Projet controller.
 *
 * @Route("commune/projets")
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
        $commune = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $projets = $em->getRepository('AppBundle:Projet')->findBy(['commune'=> $commune ]);


        
        

        return $this->render('projet/index.html.twig', array(
            'projets' => $projets
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
        $commune = $this->getUser();
        $projet = new Projet();
        $form = $this->createForm('AppBundle\Form\ProjetType', $projet);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
             $file = $projet->getImage();
             $unique = md5($file. time());

            $fileName = $unique.'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('image_directory'),
                $fileName
            );
            $e = $this->getDoctrine()->getManager();
            if ($citoyens = $e->getRepository('AppBundle:Liste')->findBy(['commune'=>$commune, 'blocked'=> false])) {
               foreach ($citoyens as $citoyen) {
                $cit=$citoyen->getCitoyen();
                $com=$citoyen->getCommune();

                $notif= new Notification();
                $notif->setContenu('Nouveau Projet');
                $notif->setDestination('m');
                $time=new \DateTime(); 
                $notif->setCitoyen($cit);
                $notif->setCommune($commune);
                $notif->setVue(false);
                $notif->setCreatedAt($time);


                $e = $this->getDoctrine()->getManager();
                $e->persist($notif);
                $e->flush();
            }
            
            

             

            }
            

            $projet->setImage($fileName);
            $projet->setDone('a');
            $projet->setCommune($commune);


                       


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
    public function showAction(Request $request ,Projet $projet )
    {
        $deleteForm = $this->createDeleteForm($projet);

        $em = $this->getDoctrine()->getManager();
        $r = $em->getRepository('AppBundle:EtatProjet')->findOneBy(['projet'=> $projet]);
        if ($r) {
            $reason=$r->getReason();
        }else{
            $reason='v';
        }
        

        return $this->render('projet/show.html.twig', array(
            'projet' => $projet,
            'npvotes'=>$projet->getVotes(),
            'votes'=> count($projet->getVotes()),
            'commentaires'=>$projet->getAllommentaires(),
            'delete_form' => $deleteForm->createView(),
            'reason'=>$reason
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
            $e = $this->getDoctrine()->getManager();
            $commentaires = $e->getRepository('AppBundle:Commentaire')->findBy(['projet' =>$projet]);

            if ($commentaires) {
                foreach ($commentaires as $commentaire ) {
                   $m = $this->getDoctrine()->getManager();
                    $m->remove($commentaire);
                    $m->flush();
                }
            }
            $et = $this->getDoctrine()->getManager();
            $etat = $et->getRepository('AppBundle:EtatProjet')->findOneBy(['projet' =>$projet]);
            if ($etat) {
                
                   $xm = $this->getDoctrine()->getManager();
                    $xm->remove($etat);
                    $xm->flush();
                
            }

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

     


    /**
     * Finds and displays a commentaire entity.
     *
     * @Route("/{id}/valider",name="commentaire_valid")
     */
    public function updateCommentAction(Request $request, Commentaire $commentaire)    {

        $em = $this->getDoctrine()->getManager();
        $commentaire = $em->getRepository('AppBundle:Commentaire')->find($commentaire);

        $commentaire->setValidation(true);

       $em->persist($commentaire);
        $em->flush();
        

        return $this->redirectToRoute('projet_show', array('id' => $commentaire->getProjet()->getId()));
    }


     /**
     * Finds and displays a commentaire entity.
     *
     * @Route("/{id}/deleteCom",name="commentaire_delete")
     */
    public function deleteCommentAction(Request $request, Commentaire $commentaire)    {

        $em = $this->getDoctrine()->getManager();
        $commentaire = $em->getRepository('AppBundle:Commentaire')->find($commentaire);

        $em = $this->getDoctrine()->getManager();
        $em->remove($commentaire);
        $em->flush();
        

        return $this->redirectToRoute('projet_show', array('id' => $commentaire->getProjet()->getId()));
    }


    /**
     * Finds and displays a projet entity.
     *
     * @Route("/{id}/done",name="projet_etat")
     */
    public function updateEtatAction(Request $request, Projet $projet)    {

        $em = $this->getDoctrine()->getManager();
        $projet = $em->getRepository('AppBundle:Projet')->find($projet);

        $projet->setDone('b');

       $em->persist($projet);
        $em->flush();
        

        return $this->redirectToRoute('projet_show', array('id' => $projet->getId()));
    }

    /**
     * Finds and displays a projet entity.
     *
     * @Route("/{id}/interrapted",name="projet_etat_in")
     */
    public function updateEtatInAction(Request $request, Projet $projet)    {

        $em = $this->getDoctrine()->getManager();
        $projet = $em->getRepository('AppBundle:Projet')->find($projet);

        $projet->setDone('c');

       $em->persist($projet);
        $em->flush();
        

        return $this->redirectToRoute('projet_show', array('id' => $projet->getId()));
    }


    /**
     * Finds and displays a projet entity.
     *
     * @Route("/{id}/newEt",name="etat_in")
     */
    public function interrAction(Request $request, Projet $projet)  
      {
        $r=$request->request->get('raison');

        $etat = new EtatProjet();
        
        $etat->setProjet($projet);
        $etat->setReason($r);

          $em = $this->getDoctrine()->getManager();
         $em->persist($etat);
        $em->flush();
        return $this->redirectToRoute('projet_show', array('id' => $projet->getId()));
    }

}
