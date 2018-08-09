<?php

namespace FactelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class InicioController extends Controller {

    /**
     * @Route("/", name="home")
     */
    public function inicioAction() {
        $em = $this->getDoctrine()->getManager();
        $emisorId = null;
        $idPtoEmision = null;
        if ($this->get("security.context")->isGranted("ROLE_EMISOR")) {
            if ($this->get("security.context")->isGranted("ROLE_EMISOR_ADMIN")) {
                $emisorId = $em->getRepository('FactelBundle:User')->findEmisorId($this->get("security.context")->gettoken()->getuser()->getId());
            } else {
                $idPtoEmision = $em->getRepository('FactelBundle:PtoEmision')->findIdPtoEmisionByUsuario($this->get("security.context")->gettoken()->getuser()->getId());
            }
            $registrados = 0;
            $autorizados = 0;
            $procesandose = 0;
            $noAutorizados = 0;


            $facturas = $em->getRepository('FactelBundle:Factura')->cantidadFacturasEstados($idPtoEmision, $emisorId);
            $ventasActual = $em->getRepository('FactelBundle:Factura')->ventaTotal($idPtoEmision, $emisorId, 2018);

            $ventaTotalActual = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
            foreach ($ventasActual as $venta) {
                $ventaTotalActual[intval($venta['mes']) - 1] = floatval($venta['ventaTotal']);
            }

            $ventaTotalAnnoAnterior = $em->getRepository('FactelBundle:Factura')->ventaTotal($idPtoEmision, $emisorId, 2017);

            $ventaTotalAnnoAnterior = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
            foreach ($ventaTotalAnnoAnterior as $venta) {
                $ventaTotalAnnoAnterior[intval($venta['mes']) - 1] = floatval($venta['ventaTotal']);
            }

            $ventasXDia = $em->getRepository('FactelBundle:Factura')->ventaTotalXDia($idPtoEmision, $emisorId);
            $ventaXDiaTotal = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
            foreach ($ventasXDia as $venta) {
                $ventaXDiaTotal[intval($venta['dia']) - 1] = floatval($venta['ventaTotal']);
            }
            
            foreach ($facturas as $obj) {
                $registrados += intval($obj['cantidad']);
                if ($obj['estado'] == "AUTORIZADO") {
                    $autorizados += intval($obj['cantidad']);
                } else if ($obj['estado'] == "PORCESANDOSE") {
                    $procesandose += intval($obj['cantidad']);
                } else if ($obj['estado'] == "DEVUELTA" || $obj['estado'] == "NO AUTORIZADO") {
                    $noAutorizados += intval($obj['cantidad']);
                }
            }

            $notaCredito = $em->getRepository('FactelBundle:NotaCredito')->cantidadNotasCreditoEstados($idPtoEmision, $emisorId);
            foreach ($notaCredito as $obj) {
                $registrados += intval($obj['cantidad']);
                if ($obj['estado'] == "AUTORIZADO") {
                    $autorizados += intval($obj['cantidad']);
                } else if ($obj['estado'] == "PORCESANDOSE") {
                    $procesandose += intval($obj['cantidad']);
                } else if ($obj['estado'] == "DEVUELTA" || $obj['estado'] == "NO AUTORIZADO") {
                    $noAutorizados += intval($obj['cantidad']);
                }
            }

            $notaDebito = $em->getRepository('FactelBundle:NotaCredito')->cantidadNotasCreditoEstados($idPtoEmision, $emisorId);
            foreach ($notaDebito as $obj) {
                $registrados += intval($obj['cantidad']);
                if ($obj['estado'] == "AUTORIZADO") {
                    $autorizados += intval($obj['cantidad']);
                } else if ($obj['estado'] == "PORCESANDOSE") {
                    $procesandose += intval($obj['cantidad']);
                } else if ($obj['estado'] == "DEVUELTA" || $obj['estado'] == "NO AUTORIZADO") {
                    $noAutorizados += intval($obj['cantidad']);
                }
            }

            $retenciones = $em->getRepository('FactelBundle:NotaCredito')->cantidadNotasCreditoEstados($idPtoEmision, $emisorId);
            foreach ($retenciones as $obj) {
                $registrados += intval($obj['cantidad']);
                if ($obj['estado'] == "AUTORIZADO") {
                    $autorizados += intval($obj['cantidad']);
                } else if ($obj['estado'] == "PORCESANDOSE") {
                    $procesandose += intval($obj['cantidad']);
                } else if ($obj['estado'] == "DEVUELTA" || $obj['estado'] == "NO AUTORIZADO") {
                    $noAutorizados += intval($obj['cantidad']);
                }
            }
            return $this->render("FactelBundle:Inicio:inicio.html.twig", array(
                        'registrados' => $registrados,
                        'autorizados' => $autorizados,
                        'procesandose' => $procesandose,
                        'noAutorizados' => $noAutorizados,
                        'ventaTotalActual' => implode(",", $ventaTotalActual),
                        'ventaTotalAnnoAnterior' => implode(",", $ventaTotalAnnoAnterior),
                'ventaXDiaTotal' =>implode(",", $ventaXDiaTotal)
            ));
        } else {
            return $this->render("FactelBundle:Inicio:inicio.html.twig", array()
            );
        }
    }

}
