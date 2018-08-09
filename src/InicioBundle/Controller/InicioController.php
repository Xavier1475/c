<?php

namespace InicioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class InicioController extends Controller
{
    /**
     * @Route("/portal")
     */
    public function indexAction()
    {
        return $this->render('InicioBundle::index.html.twig');
            
    }
}
