<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

      /**
   * @Route("/dashboard", name="dashboard")
   */
    public function showdashboardAction(Request $request)
    {
       

        return $this->render('default/dashboard.html.twig');
    }

    /**
   * @Route("/profil", name="profil")
   */
    public function showProfilAction(Request $request)
    {
       $em = $this->getDoctrine()->getManager();
        $governorats = $em->getRepository('AppBundle:Gouvernorat')->findall();

        return $this->render('espaceCommune/user.html.twig', [
            'gouvernorats'=> $governorats
          ]);
    }

    /**
   * @Route("/admin", name="admin")
   */
    public function showadminAction(Request $request)
    {
       

        return $this->render('admin/admin.html.twig');
    }
}
