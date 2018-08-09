<?php

namespace InicioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class EmailController extends Controller {

    /**
     * @Route("/enviar", name = "enviar")
     */
    public function indexAction() {
        $mailer = $this->get('mailer');

        $message = \Swift_Message::newInstance()
                ->setSubject('Hello Email')
                ->setFrom('noreplay@facilfact.com')
                ->setTo('yoelvysmh@gmail.com')
                ->setBody("hi")
        ;
       $mailer->send($message);
        return $this->render("InicioBundle::vacia.html.twig");
    }

}
