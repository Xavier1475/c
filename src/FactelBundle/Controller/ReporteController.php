<?php

namespace FactelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FactelBundle\Entity\Cliente;
use FactelBundle\Form\ClienteType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Cliente controller.
 *
 * @Route("/comprobantes/reporte")
 */
class ReporteController extends Controller {

    /**
     * Lists all Cliente entities.
     *
     * @Route("/", name="reporte")
     * @Secure(roles="ROLE_EMISOR")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        return array(
        );
    }

    /**
     * Lists all Factura entities.
     *
     * @Route("/nc", name="all_nc")
     * @Secure(roles="ROLE_EMISOR")
     * @Method("GET")
     */
    public function notaCreditoAction() {
        return $this->facturasAction("NC");
    }

    /**
     * Lists all Factura entities.
     *
     * @Route("/nd", name="all_nd")
     * @Secure(roles="ROLE_EMISOR")
     * @Method("GET")
     */
    public function notaDebitoAction() {
        return $this->facturasAction("ND");
    }

    /**
     * Lists all Factura entities.
     *
     * @Route("/cr", name="all_cr")
     * @Secure(roles="ROLE_EMISOR")
     * @Method("GET")
     */
    public function retencionAction() {
        return $this->facturasAction("CR");
    }

    /**
     * Lists all Factura entities.
     *
     * @Route("/gr", name="all_gr")
     * @Secure(roles="ROLE_EMISOR")
     * @Method("GET")
     */
    public function guiaAction() {
        return $this->facturasAction("GR");
    }

    /**
     * Lists all Factura entities.
     *
     * @Route("/todo", name="all_reporte")
     * @Secure(roles="ROLE_EMISOR")
     * @Method("GET")
     */
    public function facturasAction($comprobante = null, $sSearch = "", $excel = false) {
        if (isset($_GET['sEcho'])) {
            $sEcho = $_GET['sEcho'];
        }
        $iDisplayStart = 0;
        if (isset($_GET['iDisplayStart'])) {
            $iDisplayStart = intval($_GET['iDisplayStart']);
        }
        $iDisplayLength = 100000;
        if (isset($_GET['iDisplayLength'])) {
            $iDisplayLength = intval($_GET['iDisplayLength']);
        }

        if (isset($_GET['sSearch'])) {
            $sSearch = $_GET['sSearch'];
        }

        $em = $this->getDoctrine()->getManager();
        $emisorId = null;
        $idPtoEmision = null;
        if ($this->get("security.context")->isGranted("ROLE_EMISOR_ADMIN")) {
            $emisorId = $em->getRepository('FactelBundle:User')->findEmisorId($this->get("security.context")->gettoken()->getuser()->getId());
        } else {
            $idPtoEmision = $em->getRepository('FactelBundle:PtoEmision')->findIdPtoEmisionByUsuario($this->get("security.context")->gettoken()->getuser()->getId());
        }
        $ruta = "";
        if ($comprobante == "NC") {
            $ruta = "notacredito_show";
            $count = $em->getRepository('FactelBundle:NotaCredito')->cantidadNotasCredito($idPtoEmision, $emisorId);
            $entities = $em->getRepository('FactelBundle:NotaCredito')->findNotasCredito($sSearch, $iDisplayStart, $iDisplayLength, $idPtoEmision, $emisorId);
            $totalDisplayRecords = $count;

            if ($sSearch != "") {
                $totalDisplayRecords = count($em->getRepository('FactelBundle:NotaCredito')->findNotasCredito($sSearch, $iDisplayStart, 1000000, $idPtoEmision, $emisorId));
            }
        } else if ($comprobante == "ND") {
            $count = $em->getRepository('FactelBundle:NotaDebito')->cantidadNotasDebito($idPtoEmision, $emisorId);
            $entities = $em->getRepository('FactelBundle:NotaDebito')->findNotasDebito($sSearch, $iDisplayStart, $iDisplayLength, $idPtoEmision, $emisorId);
            $totalDisplayRecords = $count;
            $ruta = "notadebito_show";
            if ($sSearch != "") {
                $totalDisplayRecords = count($em->getRepository('FactelBundle:NotaDebito')->findNotasDebito($sSearch, $iDisplayStart, 1000000, $idPtoEmision, $emisorId));
            }
        } else if ($comprobante == "CR") {
            $ruta = "retencion_show";
            $count = $em->getRepository('FactelBundle:Retencion')->cantidadRetenciones($idPtoEmision, $emisorId);
            $entities = $em->getRepository('FactelBundle:Retencion')->findRetenciones($sSearch, $iDisplayStart, $iDisplayLength, $idPtoEmision, $emisorId);
            $totalDisplayRecords = $count;

            if ($sSearch != "") {
                $totalDisplayRecords = count($em->getRepository('FactelBundle:Retencion')->findRetenciones($sSearch, $iDisplayStart, 1000000, $idPtoEmision, $emisorId));
            }
        } else if ($comprobante == "GR") {
            $ruta = "guia_show";
            $count = $em->getRepository('FactelBundle:Guia')->cantidadGuias($idPtoEmision, $emisorId);
            $entities = $em->getRepository('FactelBundle:Guia')->findGuias($sSearch, $iDisplayStart, $iDisplayLength, $idPtoEmision, $emisorId);
            $totalDisplayRecords = $count;

            if ($sSearch != "") {
                $totalDisplayRecords = count($em->getRepository('FactelBundle:Guia')->findGuias($sSearch, $iDisplayStart, 1000000, $idPtoEmision, $emisorId));
            }
        } else {
            $ruta = "factura_show";
            $count = $em->getRepository('FactelBundle:Factura')->cantidadFacturas($idPtoEmision, $emisorId);
            $entities = $em->getRepository('FactelBundle:Factura')->findFacturas($sSearch, $iDisplayStart, $iDisplayLength, $idPtoEmision, $emisorId);
            $totalDisplayRecords = $count;

            if ($sSearch != "") {
                $totalDisplayRecords = count($em->getRepository('FactelBundle:Factura')->findFacturas($sSearch, $iDisplayStart, 1000000, $idPtoEmision, $emisorId));
            }
        }
        if ($excel) {
            return $entities;
        }
        $facturaArray = array();
        $i = 0;
        $router = $this->get("router");
        foreach ($entities as $entity) {
            $fechaAutorizacion = "";
            $fechaAutorizacion = $entity->getFechaAutorizacion() != null ? $entity->getFechaAutorizacion()->format("d/m/Y H:i:s") : "";
            $facturaArray[$i] = [$router->generate($ruta, array('id' => $entity->getId())), $entity->getEstablecimiento()->getCodigo() . "-" . $entity->getEstablecimiento()->getCodigo() . "-" . $entity->getSecuencial(), $entity->getCliente()->getNombre(), $entity->getCliente()->getIdentificacion(), $comprobante == "GR" ? $entity->getFechaIniTransporte()->format("d/m/Y") : $entity->getFechaEmision()->format("d/m/Y"), $fechaAutorizacion, $comprobante == "GR" ? 0.00 : $entity->getValorTotal(), $entity->getEstado()];
            $i++;
        }

        $arr = array(
            "iTotalRecords" => (int) $count,
            "iTotalDisplayRecords" => (int) $totalDisplayRecords,
            'aaData' => $facturaArray
        );

        $post_data = json_encode($arr);

        return new Response($post_data, 200, array('Content-Type' => 'application/json'));
    }

    /**
     * @Secure(roles="ROLE_EMISOR")
     * @Route("/excel", name="comprobante_excel")
     * @Method("GET")
     */
    public function descargarExcel() {
        $sSearch = $_GET['filtro'];
        $comprobante = $_GET['tipoComprobante'];

        $result = $this->facturasAction($comprobante, $sSearch, true);
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()
                ->setCreator("FacilFact")
                ->setLastModifiedBy("FacilFact")
                ->setTitle("Reporte Comprobante")
                ->setSubject("Reporte Comprobante")
                ->setDescription("Reporte Comprobante")
                ->setKeywords("reporte comprobante");

        $phpExcelObject->setActiveSheetIndex(0);
        $phpExcelObject->getActiveSheet()->setTitle($comprobante);

        $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('B2', 'No Doc')
                ->setCellValue('C2', 'Cliente')
                ->setCellValue('D2', 'Indentificacion')
                ->setCellValue('E2', 'F. Emision')
                ->setCellValue('F2', 'F. Autorizacion')
                ->setCellValue('G2', 'Valor')
                ->setCellValue('H2', 'Estado');

        $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('B')
                ->setWidth(30);
        $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('C')
                ->setWidth(28);
        $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('D')
                ->setWidth(15);
        $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('E')
                ->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('F')
                ->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('G')
                ->setWidth(20);
        $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('H')
                ->setWidth(20);
        $row = 3;
        foreach ($result as $item) {
            $fechaEmision = null;
            if ($comprobante == "GR") {
                $fechaEmision = $item->getFechaIniTransporte() != null && $item->getFechaIniTransporte()->format("Y") != "-0001" ? $item->getFechaIniTransporte()->format("d/m/Y") : "";
            } else {
                $fechaEmision = $item->getFechaEmision() != null && $item->getFechaEmision()->format("Y") != "-0001" ? $item->getFechaEmision()->format("d/m/Y") : "";
            }
            $fechaAutorizacion = $item->getFechaAutorizacion() != null && $item->getFechaAutorizacion()->format("Y") != "-0001" ? $item->getFechaAutorizacion()->format("d/m/Y H:i:s") : "";
            $numeroDoc = $item->getEstablecimiento()->getCodigo() . "-" . $item->getEstablecimiento()->getCodigo() . "-" . $item->getSecuencial();

            $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('B' . $row, $numeroDoc)
                    ->setCellValue('C' . $row, $item->getCliente()->getNombre())
                    ->setCellValue('D' . $row, $item->getCliente()->getIdentificacion())
                    ->setCellValue('E' . $row, $fechaEmision)
                    ->setCellValue('F' . $row, $fechaAutorizacion)
                    ->setCellValue('G' . $row, $comprobante == "GR" ? 0.00 : $item->getValorTotal())
                    ->setCellValue('H' . $row, $item->getEstado());


            $row++;
        }

        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        $response = $this->get('phpexcel')->createStreamedResponse($writer);

        $dispositionHeader = $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'comprobantes.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }

}
