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

require('fpdf.php');

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
     * Lists all Cliente entities.
     *
     * @Route("/reporte-ventas", name="reporte_ventas")
     * @Secure(roles="ROLE_EMISOR")
     * @Method("GET")
     * @Template()
     */
    public function reporteVentasAction() {
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
                ->setCellValue('H2', 'Estado')
                ->setCellValue('I2', 'Num Autorizacion');

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
        $phpExcelObject->setActiveSheetIndex(0)
                ->getColumnDimension('I')
                ->setWidth(30);
        $row = 3;
        foreach ($result as $item) {
            $fechaEmision = null;
            if ($comprobante == "GR") {
                $fechaEmision = $item->getFechaIniTransporte() != null && $item->getFechaIniTransporte()->format("Y") != "-0001" ? $item->getFechaIniTransporte()->format("d/m/Y") : "";
            } else {
                $fechaEmision = $item->getFechaEmision() != null && $item->getFechaEmision()->format("Y") != "-0001" ? $item->getFechaEmision()->format("d/m/Y") : "";
            }
            $fechaAutorizacion = $item->getFechaAutorizacion() != null && $item->getFechaAutorizacion()->format("Y") != "-0001" ? $item->getFechaAutorizacion()->format("d/m/Y H:i:s") : "";
            $numeroDoc = $item->getEstablecimiento()->getCodigo() . "-" . $item->getPtoEmision()->getCodigo() . "-" . $item->getSecuencial();
            $numAutorizacion = $item->getNumeroAutorizacion() != null ? $item->getNumeroAutorizacion() : "";

            $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('B' . $row, $numeroDoc)
                    ->setCellValue('C' . $row, $item->getCliente()->getNombre())
                    ->setCellValue('D' . $row, $item->getCliente()->getIdentificacion())
                    ->setCellValue('E' . $row, $fechaEmision)
                    ->setCellValue('F' . $row, $fechaAutorizacion)
                    ->setCellValue('G' . $row, $comprobante == "GR" ? 0.00 : $item->getValorTotal())
                    ->setCellValue('H' . $row, $item->getEstado())
                    ->setCellValue('I' . $row, $numAutorizacion);


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

    /**
     * Lists all Factura entities.
     *
     * @Route("/ventas-fechas", name="reporte_ventas_fechas")
     * @Secure(roles="ROLE_EMISOR")
     * @Method("GET")
     */
    public function reporteVentasFechasAction($comprobante = null, $sSearch = "", $pdf = false) {
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

        $ruta = "factura_show";
        $count = $em->getRepository('FactelBundle:Factura')->cantidadFacturas($idPtoEmision, $emisorId, true);
        $entities = $em->getRepository('FactelBundle:Factura')->findFacturas($sSearch, $iDisplayStart, $iDisplayLength, $idPtoEmision, $emisorId, true);
        $totalDisplayRecords = $count;

        if ($sSearch != "") {
            $totalDisplayRecords = count($em->getRepository('FactelBundle:Factura')->findFacturas($sSearch, $iDisplayStart, 1000000, $idPtoEmision, $emisorId, true));
        }


        $facturaArray = array();
        $i = 0;
        $entity = new \FactelBundle\Entity\Factura();
        foreach ($entities as $entity) {
            if ($entity->getEstado() == "AUTORIZADO") {
                $facturaArray[$i] = [$entity->getEstablecimiento()->getCodigo() . "-" . $entity->getEstablecimiento()->getCodigo() . "-" . $entity->getSecuencial(),
                    $entity->getCliente()->getNombre(), $this->FormaPago($entity->getFormaPago()), $entity->getFechaEmision()->format("d/m/Y"),
                    $entity->getValorTotal(), $entity->getEmisor()->getRazonSocial()];
                $i++;
            }
        }

        if ($pdf) {
            return $facturaArray;
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
     * @Route("/pdf", name="comprobante_pdf")
     * @Method("GET")
     */
    public function descargarPdf() {
        $sSearch = $_GET['filtro'];
        $comprobante = $_GET['tipoComprobante'];

        $result = $this->reporteVentasFechasAction($comprobante, $sSearch, true);
        $pdf = new \FPDF();
        $header = array('No. Doc', 'Cliente', 'Forma Pago', 'F. Emision', 'Valor Total');
        $data = $result;
        $pdf->SetFont('Arial', '', 8);
        $pdf->AddPage();
        $emisor = "";
        if (count($data) > 0) {
            $emisor = $data[0][5];
        }
        setlocale(LC_ALL, 'es_AR');
        $now = date("d/m/Y H:i:s");
        $pdf->Cell(130, 10, "Emisor: " . $emisor);
        $pdf->Cell(40, 10, $now, 0, 1, 'C');
        //Anchuras de las columnas
        $w = array(30, 70, 45, 25, 20);
        //Cabeceras
        for ($i = 0; $i < count($header); $i++)
            $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
        $pdf->Ln();
        //Datos
        $total = 0;
        foreach ($data as $row) {
            $pdf->Cell($w[0], 6, $row[0], 'LR');
            $pdf->Cell($w[1], 6, $row[1], 'LR');
            $pdf->Cell($w[2], 6, $row[2], 'LR', 0, 'C');
            $pdf->Cell($w[3], 6, $row[3], 'LR', 0, 'C');
            $pdf->Cell($w[4], 6, $row[4], 'LR');
            $pdf->Ln();
            $total += floatval($row[4]);
        }
        $pdf->Cell(array_sum($w), 0, '', 'T');
        $pdf->Ln();
        $pdf->Cell($w[0], 6, '', 'LR');
        $pdf->Cell($w[1], 6, '', 'LR');
        $pdf->Cell($w[2], 6, '', 'LR', 0, 'C');
        $pdf->Cell($w[3], 6, 'Total', 'LR', 0, 'C');
        $pdf->Cell($w[4], 6, $total, 'LR');
        $pdf->Ln();
        //Línea de cierre
        $pdf->Cell(array_sum($w), 0, '', 'T');


        $response = new Response();
        //then send the headers to foce download the zip file
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="Reporte Ventas PDF.pdf"');
        $response->headers->set('Pragma', "no-cache");
        $response->headers->set('Expires', "0");
        $response->headers->set('Content-Transfer-Encoding', "binary");
        $response->sendHeaders();
        $response->setContent($pdf->Output("Reporte PDF.pdf", "S"));
        return $response;
    }

    private function FormaPago($cod) {

        $formaDePago = "";
        if ($cod == ("01")) {
            $formaDePago = "EFECTIVO";
        } else if ($cod == ("15")) {
            $formaDePago = "COMPENSACIÓN DE DEUDAS";
        } else if ($cod == ("16")) {
            $formaDePago = "TARJETA DE DÉBITO";
        } else if ($cod == ("17")) {
            $formaDePago = "DINERO ELECTRÓNICO";
        } else if ($cod == ("18")) {
            $formaDePago = "TARJETA PREPAGO";
        } else if ($cod == ("19")) {
            $formaDePago = "TARJETA DE CRÉDITO";
        } else if ($cod == ("20")) {
            $formaDePago = "OTRAS FROMA PAGO";
        } else if ($cod == ("21")) {
            $formaDePago = "ENDOSO DE TÍTULOS";
        }
        return $formaDePago;
    }

}
