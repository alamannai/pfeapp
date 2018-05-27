<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
   * @Route("/", name="index")
   */
    public function showAction(Request $request)
    {
       

        return $this->render('default/index.html.twig');
    }

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
       

        return $this->render('espaceCommune/user.html.twig');
    }

    /**
   * @Route("/admin", name="admin")
   */
    public function showadminAction(Request $request)
    {
       

        return $this->render('admin/admin.html.twig');
    }
}
