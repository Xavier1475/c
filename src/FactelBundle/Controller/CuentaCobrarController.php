<?php

namespace FactelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FactelBundle\Entity\Factura;
use FactelBundle\Form\EmisorType;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Factura controller.
 *
 * @Route("/comprobantes/pagocliente")
 */

class ReporteController extends Controller {

   /* if ($this->get("security.context")->isGranted("ROLE_ADMIN")) {
        $em = $this->getDoctrine()->getManager();
        $planes = $em->getRepository('FactelBundle:Factura')->findBy(array("estado" => true));
        $entities = $em->getRepository('FactelBundle:Emisor')->findAll();
        $deleteForms = array();
        foreach ($entities as $entity) {
            $deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
        }
        return array(
            'entities' => $entities,
            'deleteForms' => $deleteForms,
            'planes' => $planes
        );
    } else {
        $user = $this->get("security.context")->getToken()->getUser();
        */
       /**
     * Lists all Emisor entities.
     *
     * @Route("/", name="index")
     * @Secure(roles="ROLE_EMISOR")
     * @Method("GET")
     */
        public function indexPago(){
            return $this->render(
                'FactelBundle:PagoCliente:index.html.twig' //array('entity' => $user->getEmisor())
                );
        }
        
    
}


