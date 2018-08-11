<?php

namespace FactelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FactelBundle\Entity\Factura;
use FactelBundle\Entity\FacturaHasProducto;
use FactelBundle\Entity\Impuesto;
use FactelBundle\Entity\CampoAdicional;
use FactelBundle\Form\FacturaType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;
use FactelBundle\Util;

require_once 'ProcesarComprobanteElectronico.php';

/**
 * Factura controller.
 *
 * @Route("/comprobantes/factura")
 */
class FacturaController extends Controller {

    /**
     * Lists all Emisor entities.
     *
     * @Route("/", name="factura")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {

        return array();
    }

    /**
     * Lists all Factura entities.
     *
     * @Route("/facturas", name="all_factura")
     * @Secure(roles="ROLE_EMISOR")
     * @Method("GET")
     */
    public function facturasAction() {
        if (isset($_GET['sEcho'])) {
            $sEcho = $_GET['sEcho'];
        }
        if (isset($_GET['iDisplayStart'])) {
            $iDisplayStart = intval($_GET['iDisplayStart']);
        }
        if (isset($_GET['iDisplayLength'])) {
            $iDisplayLength = intval($_GET['iDisplayLength']);
        }
        $sSearch = "";
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
        $count = $em->getRepository('FactelBundle:Factura')->cantidadFacturas($idPtoEmision, $emisorId);
        $entities = $em->getRepository('FactelBundle:Factura')->findFacturas($sSearch, $iDisplayStart, $iDisplayLength, $idPtoEmision, $emisorId);
        $totalDisplayRecords = $count;

        if ($sSearch != "") {
            $totalDisplayRecords = count($em->getRepository('FactelBundle:Factura')->findFacturas($sSearch, $iDisplayStart, 1000000, $idPtoEmision, $emisorId));
        }
        $facturaArray = array();
        $i = 0;
        foreach ($entities as $entity) {
            $fechaAutorizacion = "";
            $fechaAutorizacion = $entity->getFechaAutorizacion() != null ? $entity->getFechaAutorizacion()->format("d/m/Y H:i:s") : "";
            $facturaArray[$i] = [$entity->getId(), $entity->getEstablecimiento()->getCodigo() . "-" . $entity->getPtoEmision()->getCodigo() . "-" . $entity->getSecuencial(), $entity->getCliente()->getNombre(), $entity->getFechaEmision()->format("d/m/Y"), $fechaAutorizacion, $entity->getValorTotal(), $entity->getEstado()];
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
     * Creates a new Factura entity.
     *
     * @Route("/procesar/{id}", name="factura_procesar")
     * @Method("GET")
     * @Secure(roles="ROLE_EMISOR")
     */
    public function procesarAccion($id) {
        $entity = new Factura();
        $procesarComprobanteElectronico = new \ProcesarComprobanteElectronico();
        $respuesta = null;
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FactelBundle:Factura')->findFacturaById($id);

        if (!$entity) {
            throw $this->createNotFoundException('No existe la factura con ID = ' + $id);
        }
        if ($entity->getEstado() == "AUTORIZADO") {
            $this->get('session')->getFlashBag()->add(
                    'notice', "Este comprobante electrónico ya fue autorizado"
            );
            return $this->redirect($this->generateUrl('factura_show', array('id' => $entity->getId())));
        }
        $emisor = $entity->getEmisor();
        $configApp = new \configAplicacion();
        $configApp->dirFirma = $emisor->getDirFirma();
        $configApp->passFirma = $emisor->getPassFirma();
        $configApp->dirAutorizados = $emisor->getDirDocAutorizados();
        $configApp->dirLogo = $emisor->getDirLogo();

        $configCorreo = new \configCorreo();
        $configCorreo->correoAsunto = "Nuevo Comprobante Electrónico";
        $configCorreo->correoHost = $emisor->getServidorCorreo();
        $configCorreo->correoPass = $emisor->getPassCorreo();
        $configCorreo->correoPort = $emisor->getPuerto();
        $configCorreo->correoRemitente = $emisor->getCorreoRemitente();
        $configCorreo->sslHabilitado = $emisor->getSSL();

        if ($entity->getEstado() != "PROCESANDOSE") {
            $factura = new \factura();
            $factura->configAplicacion = $configApp;
            $factura->configCorreo = $configCorreo;

            $factura->ambiente = $entity->getAmbiente();
            $factura->tipoEmision = $entity->getTipoEmision();
            $factura->razonSocial = $emisor->getRazonSocial();
            if ($entity->getEstablecimiento()->getNombreComercial() != "") {
                $factura->nombreComercial = $entity->getEstablecimiento()->getNombreComercial();
            }else if($emisor->getNombreComercial() != ""){
                 $factura->nombreComercial = $emisor->getNombreComercial();
            }
            $factura->ruc = $emisor->getRuc(); //[Ruc]
            $factura->codDoc = "01";
            $factura->establecimiento = $entity->getEstablecimiento()->getCodigo();
            $factura->ptoEmision = $entity->getPtoEmision()->getCodigo();
            $factura->secuencial = $entity->getSecuencial();
            $factura->fechaEmision = $entity->getFechaEmision()->format("d/m/Y");
            $factura->dirMatriz = $emisor->getDireccionMatriz();
            $factura->dirEstablecimiento = $entity->getEstablecimiento()->getDireccion();
            if ($emisor->getContribuyenteEspecial() != "") {
                $factura->contribuyenteEspecial = $emisor->getContribuyenteEspecial();
            }
            $factura->obligadoContabilidad = $emisor->getObligadoContabilidad();
            $factura->tipoIdentificacionComprador = $entity->getCliente()->getTipoIdentificacion();
            $factura->razonSocialComprador = $entity->getCliente()->getNombre();
            $factura->identificacionComprador = $entity->getCliente()->getIdentificacion();
            $factura->totalSinImpuestos = $entity->getTotalSinImpuestos();
            $factura->totalDescuento = $entity->getTotalDescuento();



            $factura->propina = $entity->getPropina();
            $factura->importeTotal = $entity->getValorTotal();
            $factura->moneda = "DOLAR"; //DOLAR
            $pagos = array();

            $pago = new \pago();
            $pago->formaPago = $entity->getFormaPago();
            $pago->total = $entity->getValorTotal();
            $pagos [] = $pago;

            $factura->pagos = $pagos;

            $codigoPorcentajeIVA = "";
            $detalles = array();
            $facturasHasProducto = $entity->getFacturasHasProducto();
            $impuestosTotalICE = array();
            $baseImponibleICE = array();
            $impuestosTotalIRBPNR = array();
            $baseImponibleIRBPNR = array();
            foreach ($facturasHasProducto as $facturasHasProducto) {
                $producto = new \FactelBundle\Entity\Producto();
                $producto = $facturasHasProducto->getProducto();
                $detalleFactura = new \detalleFactura();
                $detalleFactura->codigoPrincipal = $facturasHasProducto->getCodigoProducto();
                if ($producto->getCodigoAuxiliar() != "") {
                    $detalleFactura->codigoAuxiliar = $producto->getCodigoAuxiliar();
                }
                $detalleFactura->descripcion = $facturasHasProducto->getNombre();
                $detalleFactura->cantidad = $facturasHasProducto->getCantidad();
                $detalleFactura->precioUnitario = $facturasHasProducto->getPrecioUnitario();
                $detalleFactura->descuento = $facturasHasProducto->getDescuento();
                $detalleFactura->precioTotalSinImpuesto = $facturasHasProducto->getValorTotal();

                $impuestos = array();
                $impuestosProducto = $facturasHasProducto->getImpuestos();
                foreach ($impuestosProducto as $impuestoProducto) {

                    $impuesto = new \impuesto(); // Impuesto del detalle
                    $impuesto->codigo = $impuestoProducto->getCodigo();
                    if ($impuestoProducto->getCodigo() == "2" && $impuestoProducto->getValor() > 0) {
                        $codigoPorcentajeIVA = $impuestoProducto->getCodigoPorcentaje();
                    }
                    $impuesto->codigoPorcentaje = $impuestoProducto->getCodigoPorcentaje();
                    $impuesto->tarifa = $impuestoProducto->getTarifa();
                    $impuesto->baseImponible = $impuestoProducto->getBaseImponible();
                    $impuesto->valor = $impuestoProducto->getValor();
                    $impuestos[] = $impuesto;

                    if ($impuestoProducto->getCodigo() == "3") {
                        if (isset($impuestosTotalICE[$impuestoProducto->getCodigoPorcentaje()])) {
                            $impuestosTotalICE[$impuestoProducto->getCodigoPorcentaje()] += $impuestoProducto->getValor();
                            $baseImponibleICE[$impuestoProducto->getCodigoPorcentaje()] += $impuestoProducto->getBaseImponible();
                        } else {
                            $impuestosTotalICE[$impuestoProducto->getCodigoPorcentaje()] = $impuestoProducto->getValor();
                            $baseImponibleICE[$impuestoProducto->getCodigoPorcentaje()] = $impuestoProducto->getBaseImponible();
                        }
                    }
                    if ($impuestoProducto->getCodigo() == "5") {
                        if (isset($impuestosTotalIRBPNR[$impuestoProducto->getCodigoPorcentaje()])) {
                            $impuestosTotalIRBPNR[$impuestoProducto->getCodigoPorcentaje()] += $impuestoProducto->getValor();
                            $baseImponibleIRBPNR[$impuestoProducto->getCodigoPorcentaje()] += $impuestoProducto->getBaseImponible();
                        } else {
                            $impuestosTotalIRBPNR[$impuestoProducto->getCodigoPorcentaje()] = $impuestoProducto->getValor();
                            $baseImponibleIRBPNR[$impuestoProducto->getCodigoPorcentaje()] = $impuestoProducto->getBaseImponible();
                        }
                    }
                }
                $detalleFactura->impuestos = $impuestos;
                $detalles[] = $detalleFactura;
            }

            foreach ($impuestosTotalICE as $clave => $valor) {
                $totalImpuesto = new \totalImpuesto();
                $totalImpuesto->codigo = "3";
                $totalImpuesto->codigoPorcentaje = (string) $clave;
                $totalImpuesto->baseImponible = sprintf("%01.2f", $baseImponibleICE[$clave]);
                $totalImpuesto->valor = sprintf("%01.2f", $valor);

                $totalImpuestoArray[] = $totalImpuesto;
            }

            foreach ($impuestosTotalIRBPNR as $clave => $valor) {
                $totalImpuesto = new \totalImpuesto();
                $totalImpuesto->codigo = "5";
                $totalImpuesto->codigoPorcentaje = (string) $clave;
                $totalImpuesto->baseImponible = sprintf("%01.2f", $baseImponibleIRBPNR[$clave]);
                $totalImpuesto->valor = sprintf("%01.2f", $valor);

                $totalImpuestoArray[] = $totalImpuesto;
            }

            $totalImpuestoArray = array();
            if ($entity->getSubtotal12() > 0) {
                $totalImpuesto = new \totalImpuesto();
                $totalImpuesto->codigo = "2";
                $totalImpuesto->codigoPorcentaje = $codigoPorcentajeIVA;
                $totalImpuesto->baseImponible = $entity->getSubtotal12();
                $totalImpuesto->valor = $entity->getIva12();

                $totalImpuestoArray[] = $totalImpuesto;
            }
            if ($entity->getSubtotal0() > 0) {
                $totalImpuesto = new \totalImpuesto();
                $totalImpuesto->codigo = "2";
                $totalImpuesto->codigoPorcentaje = "0";
                $totalImpuesto->baseImponible = $entity->getSubtotal0();
                $totalImpuesto->valor = "0.00";

                $totalImpuestoArray[] = $totalImpuesto;
            }
            if ($entity->getSubtotalExentoIVA() > 0) {
                $totalImpuesto = new \totalImpuesto();
                $totalImpuesto->codigo = "2";
                $totalImpuesto->codigoPorcentaje = "7";
                $totalImpuesto->baseImponible = $entity->getSubtotalExentoIVA();
                $totalImpuesto->valor = "0.00";

                $totalImpuestoArray[] = $totalImpuesto;
            }
            if ($entity->getSubtotalNoIVA() > 0) {
                $totalImpuesto = new \totalImpuesto();
                $totalImpuesto->codigo = "2";
                $totalImpuesto->codigoPorcentaje = "6";
                $totalImpuesto->baseImponible = $entity->getSubtotalNoIVA();
                $totalImpuesto->valor = "0.00";

                $totalImpuestoArray[] = $totalImpuesto;
            }

            $factura->detalles = $detalles;
            $factura->totalConImpuesto = $totalImpuestoArray;

            $camposAdicionales = array();
            foreach ($entity->getComposAdic() as $campoAdic) {
                $campoAdicional = new \campoAdicional();
                $campoAdicional->nombre = $campoAdic->getNombre();
                $campoAdicional->valor = $campoAdic->getValor();

                $camposAdicionales [] = $campoAdic;
            }
            $cliente = $entity->getCliente();
            if ($cliente->getDireccion() != "") {
                $campoAdic = new \campoAdicional();
                $campoAdic->nombre = "Dirección";
                $campoAdic->valor = $cliente->getDireccion();

                $camposAdicionales [] = $campoAdic;
            }
            if ($cliente->getCelular() != "") {
                $campoAdic = new \campoAdicional();
                $campoAdic->nombre = "Teléfono";
                $campoAdic->valor = $cliente->getCelular();

                $camposAdicionales [] = $campoAdic;
            }
            if ($cliente->getTipoIdentificacion() != "07" && $cliente->getCorreoElectronico() != "") {
                $campoAdic = new \campoAdicional();
                $campoAdic->nombre = "Email";
                $campoAdic->valor = $cliente->getCorreoElectronico();

                $camposAdicionales [] = $campoAdic;
            }
            if ($entity->getObservacion() != "") {
                $campoAdic = new \campoAdicional();
                $campoAdic->nombre = "Observacion";
                $campoAdic->valor = $entity->getObservacion();

                $camposAdicionales [] = $campoAdic;
            }
            if (count($camposAdicionales) > 0) {
                $factura->infoAdicional = $camposAdicionales;
            }

            $procesarComprobante = new \procesarComprobante();
            $procesarComprobante->comprobante = $factura;

            if (!$entity->getFirmado()) {
                $procesarComprobante->envioSRI = false;
                $respuesta = $procesarComprobanteElectronico->procesarComprobante($procesarComprobante);
                if ($respuesta->return->estadoComprobante == "FIRMADO") {
                    $entity->setFirmado(true);
                    $procesarComprobante->envioSRI = true;
                    $respuesta = $procesarComprobanteElectronico->procesarComprobante($procesarComprobante);
                    if ($respuesta->return->estadoComprobante == "DEVUELTA" || $respuesta->return->estadoComprobante == "NO AUTORIZADO") {
                        $entity->setEnviarSiAutorizado(true);
                    }
                }
            } else if ($entity->getEstado() == "ERROR") {
                $procesarComprobante->envioSRI = true;
                $respuesta = $procesarComprobanteElectronico->procesarComprobante($procesarComprobante);
                if ($respuesta->return->estadoComprobante == "DEVUELTA" || $respuesta->return->estadoComprobante == "NO AUTORIZADO") {
                    $entity->setEnviarSiAutorizado(true);
                }
            } else if ($entity->getEnviarSiAutorizado()) {
                $procesarComprobante->envioSRI = true;
                $respuesta = $procesarComprobanteElectronico->procesarComprobante($procesarComprobante);
                if ($respuesta->return->estadoComprobante == "AUTORIZADO") {
                    $procesarComprobante->envioSRI = false;
                    $procesarComprobanteElectronico->procesarComprobante($procesarComprobante);
                }
            }
        } else {
            $comprobantePendiente = new \comprobantePendiente();

            $comprobantePendiente->configAplicacion = $configApp;
            $comprobantePendiente->configCorreo = $configCorreo;

            $comprobantePendiente->ambiente = $entity->getAmbiente();
            $comprobantePendiente->codDoc = "01";
            $comprobantePendiente->establecimiento = $entity->getEstablecimiento()->getCodigo();
            $comprobantePendiente->fechaEmision = $entity->getFechaEmision()->format("d/m/Y");
            $comprobantePendiente->ptoEmision = $entity->getPtoEmision()->getCodigo();
            $comprobantePendiente->ruc = $emisor->getRuc();
            $comprobantePendiente->secuencial = $entity->getSecuencial();
            $comprobantePendiente->tipoEmision = $entity->getTipoEmision();

            $procesarComprobantePendiente = new \procesarComprobantePendiente();
            $procesarComprobantePendiente->comprobantePendiente = $comprobantePendiente;

            $respuesta = $procesarComprobanteElectronico->procesarComprobantePendiente($procesarComprobantePendiente);
        }


        if ($respuesta->return->mensajes != null) {
            $mensajesArray = array();
            if (is_array($respuesta->return->mensajes)) {
                $mensajesArray = $respuesta->return->mensajes;
            } else {
                $mensajesArray[] = $respuesta->return->mensajes;
            }
            foreach ($mensajesArray as $mensaje) {
                if ($mensaje->identificador == "43") {
                    $comprobantePendiente = new \comprobantePendiente();

                    $comprobantePendiente->configAplicacion = $configApp;
                    $comprobantePendiente->configCorreo = $configCorreo;

                    $comprobantePendiente->ambiente = $entity->getAmbiente();
                    $comprobantePendiente->codDoc = "01";
                    $comprobantePendiente->establecimiento = $entity->getEstablecimiento()->getCodigo();
                    $comprobantePendiente->fechaEmision = $entity->getFechaEmision()->format("d/m/Y");
                    $comprobantePendiente->ptoEmision = $entity->getPtoEmision()->getCodigo();
                    $comprobantePendiente->ruc = $emisor->getRuc();
                    $comprobantePendiente->secuencial = $entity->getSecuencial();
                    $comprobantePendiente->tipoEmision = $entity->getTipoEmision();

                    $procesarComprobantePendiente = new \procesarComprobantePendiente();
                    $procesarComprobantePendiente->comprobantePendiente = $comprobantePendiente;

                    $respuesta = $procesarComprobanteElectronico->procesarComprobantePendiente($procesarComprobantePendiente);

                    break;
                }
            }
        }
        $entity->setNumeroAutorizacion($respuesta->return->numeroAutorizacion);

        if ($respuesta->return->fechaAutorizacion != "") {
            $fechaAutorizacion = str_replace("/", "-", $respuesta->return->fechaAutorizacion);
            $entity->setFechaAutorizacion(new \DateTime($fechaAutorizacion));
        }

        $entity->setEstado($respuesta->return->estadoComprobante);
        if ($entity->getEstado() == "AUTORIZADO") {
            $entity->setNombreArchivo("FAC" . $entity->getEstablecimiento()->getCodigo() . "-" . $entity->getPtoEmision()->getCodigo() . "-" . $entity->getSecuencial());
        }
        $mensajes = $entity->getMensajes();
        foreach ($mensajes as $mensaje) {
            $em->remove($mensaje);
        }
        if ($respuesta->return->mensajes != null) {
            $mensajesArray = array();
            if (is_array($respuesta->return->mensajes)) {
                $mensajesArray = $respuesta->return->mensajes;
            } else {
                $mensajesArray[] = $respuesta->return->mensajes;
            }
            foreach ($mensajesArray as $mensaje) {
                $mensajeGenerado = new \FactelBundle\Entity\Mensaje();
                $mensajeGenerado->setIdentificador($mensaje->identificador);
                $mensajeGenerado->setMensaje($mensaje->mensaje);
                $mensajeGenerado->setInformacionAdicional($mensaje->informacionAdicional);
                $mensajeGenerado->setTipo($mensaje->tipo);
                $mensajeGenerado->setFactura($entity);
                $em->persist($mensajeGenerado);
            }
        }
        $em->persist($entity);
        $em->flush();

        return $this->redirect($this->generateUrl('factura_show', array('id' => $entity->getId())));
    }

    /**
     * Creates a new Factura entity.
     *
     * @Route("/enviarEmail/{id}", name="factura_enviar_email")
     * @Method("POST")
     * @Secure(roles="ROLE_EMISOR")
     */
    public function sendEmail(Request $request, $id) {
        $destinatario = $request->request->get("email");
        
        $procesarComprobanteElectronico = new \ProcesarComprobanteElectronico();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FactelBundle:Factura')->findFacturaById($id);

        $comprobantePendiente = new \comprobantePendiente();
        $emisor = $entity->getEmisor();
        $configApp = new \configAplicacion();
        $configApp->dirFirma = $emisor->getDirFirma();
        $configApp->passFirma = $emisor->getPassFirma();
        $configApp->dirAutorizados = $emisor->getDirDocAutorizados();
        $configApp->dirLogo = $emisor->getDirLogo();

        $configCorreo = new \configCorreo();
        $configCorreo->correoAsunto = "Nuevo Comprobante Electrónico";
        $configCorreo->correoHost = $emisor->getServidorCorreo();
        $configCorreo->correoPass = $emisor->getPassCorreo();
        $configCorreo->correoPort = $emisor->getPuerto();
        $configCorreo->correoRemitente = $emisor->getCorreoRemitente();
        $configCorreo->sslHabilitado = $emisor->getSSL();

        $comprobantePendiente->configAplicacion = $configApp;
        $comprobantePendiente->configCorreo = $configCorreo;

        $comprobantePendiente->ambiente = $entity->getAmbiente();
        $comprobantePendiente->codDoc = "01";
        $comprobantePendiente->establecimiento = $entity->getEstablecimiento()->getCodigo();
        $comprobantePendiente->fechaEmision = $entity->getFechaEmision()->format("d/m/Y");
        $comprobantePendiente->ptoEmision = $entity->getPtoEmision()->getCodigo();
        $comprobantePendiente->ruc = $emisor->getRuc();
        $comprobantePendiente->secuencial = $entity->getSecuencial();
        $comprobantePendiente->tipoEmision = $entity->getTipoEmision();
        $comprobantePendiente->enviarEmail = true;
        if($destinatario != null && $destinatario != ''){
          $comprobantePendiente->otrosDestinatarios = $destinatario;  
        }
        $procesarComprobantePendiente = new \procesarComprobantePendiente();
        $procesarComprobantePendiente->comprobantePendiente = $comprobantePendiente;

        $respuesta = $procesarComprobanteElectronico->procesarComprobantePendiente($procesarComprobantePendiente);

        if ($respuesta->return->mensajes != null) {
            $mensajesArray = array();
            if (is_array($respuesta->return->mensajes)) {
                $mensajesArray = $respuesta->return->mensajes;
            } else {
                $mensajesArray[] = $respuesta->return->mensajes;
            }

            foreach ($mensajesArray as $mensaje) {
                $this->get('session')->getFlashBag()->add(
                        'notice', $mensaje->mensaje . ". " . $mensaje->informacionAdicional
                );
            }
        } else {
            $this->get('session')->getFlashBag()->add(
                    'confirm', "Correo enviado con exito"
            );
        }
        return $this->redirect($this->generateUrl('factura_show', array('id' => $entity->getId())));
    }

    /**
     * Creates a new Factura entity.
     *
     * @Route("/", name="factura_create")
     * @Method("POST")
     * @Secure(roles="ROLE_EMISOR")
     * @Template("FactelBundle:Factura:new.html.twig")
     */
    public function createAction(Request $request) {

        $secuencial = $request->request->get("secuencial");
        $fechaEmision = $request->request->get("fechaEmision");
        $idCliente = $request->request->get("idCliente");
        $nombre = $request->request->get("nombre");
        $celular = $request->request->get("celular");
        $email = $request->request->get("email");
        $tipoIdentificacion = $request->request->get("tipoIdentificacion");
        $identificacion = $request->request->get("identificacion");
        $direccion = $request->request->get("direccion");
        $nuevoCliente = $request->request->get("nuevoCliente");
        $idFactura = $request->request->get("idFactura");
        $formaPago = $request->request->get("formaPago");
        $observacion = $request->request->get("observacion");
        $texto = "";
        $campos = "";
        $cantidadErrores = 0;
        if ($secuencial == '') {
            $campos .= "Secuencial, ";
            $cantidadErrores++;
        }
        if ($fechaEmision == '') {
            $campos .= "Fecha Emision, ";
            $cantidadErrores++;
        }
        if ($nombre == '') {
            $campos .= "Nombre Cliente, ";
            $cantidadErrores++;
        }
        if ($tipoIdentificacion == '') {
            $campos .= "Tipo Identificacion, ";
            $cantidadErrores++;
        }
        if ($identificacion == '') {
            $campos .= "Identificacion, ";
            $cantidadErrores++;
        }
        if ($formaPago == '') {
            $campos .= "Forma Pago, ";
            $cantidadErrores++;
        }
        if ($cantidadErrores > 0) {
            if ($cantidadErrores == 1) {
                $texto = "El campo <strong>" . $campos . "</strong> no puede estar vacios";
            } else {
                $texto = "Los campos " . $campos . " no pueden estar vacios";
            }
            $this->get('session')->getFlashBag()->add(
                    'notice', $texto
            );

            return $this->redirect($this->generateUrl('factura_new', array()));
        }
        $em = $this->getDoctrine()->getManager();
        if ($idFactura != null && $idFactura != '') {
            $entity = new Factura();
            $entity = $em->getRepository('FactelBundle:Factura')->find($idFactura);
            if (!is_null($entity)) {
                $mensajes = $entity->getMensajes();
                foreach ($mensajes as $mensaje) {
                    $em->remove($mensaje);
                }
                $facturasHasProducto = $entity->getFacturasHasProducto();
                foreach ($facturasHasProducto as $facturaHasProducto) {
                    foreach ($facturaHasProducto->getImpuestos() as $impuesto) {
                        $em->remove($impuesto);
                    }
                    $em->remove($facturaHasProducto);
                }
                $em->flush();
            }
        } else {
            $entity = new Factura();
        }



        $ptoEmision = $em->getRepository('FactelBundle:PtoEmision')->findPtoEmisionEstabEmisorByUsuario($this->get("security.context")->gettoken()->getuser()->getId());

        if ($ptoEmision != null && count($ptoEmision) > 0) {
            $establecimiento = $ptoEmision[0]->getEstablecimiento();
            $emisor = $establecimiento->getEmisor();

            $entity->setEstado("CREADA");
            $entity->setAmbiente($emisor->getAmbiente());
            $entity->setTipoEmision($emisor->getTipoEmision());
            $entity->setSecuencial($secuencial);
            $entity->setClaveAcceso($this->claveAcceso($entity, $emisor, $establecimiento, $ptoEmision[0], $fechaEmision));
            $entity->setObservacion($observacion);
            $fechaModificada = str_replace("/", "-", $fechaEmision);
            $fecha = new \DateTime($fechaModificada);
            $entity->setFechaEmision($fecha);
            $cliente = $em->getRepository('FactelBundle:Cliente')->find($idCliente);
            if ($nuevoCliente) {
                $emisorId = $this->get("security.context")->gettoken()->getuser()->getEmisor()->getId();
                if ($em->getRepository('FactelBundle:Cliente')->findBy(array("identificacion" => $identificacion, "emisor" => $emisorId)) != null) {
                    $this->get('session')->getFlashBag()->add(
                            'notice', "La identificación del cliente ya se encuentra resgistrada. Utilice la opción de búsqueda"
                    );
                    return $this->redirect($this->generateUrl('factura_new', array()));
                }
                $cliente = new \FactelBundle\Entity\Cliente();

                $emisor = $em->getRepository('FactelBundle:Emisor')->find($emisorId);
                $cliente->setEmisor($emisor);
            }

            $cliente->setNombre($nombre);
            $cliente->setTipoIdentificacion($tipoIdentificacion);
            $cliente->setIdentificacion($identificacion);
            $cliente->setCelular($celular);
            $cliente->setCorreoElectronico($email);
            $cliente->setDireccion($direccion);
            $em->persist($cliente);
            $em->flush();


            $entity->setCliente($cliente);
            $entity->setEmisor($emisor);
            $entity->setEstablecimiento($establecimiento);
            $entity->setPtoEmision($ptoEmision[0]);

            $subTotalSinImpuesto = 0;
            $subTotal12 = 0;
            $subTotal0 = 0;
            $subTotaNoObjeto = 0;
            $subTotaExento = 0;
            $descuento = 0;
            $ice = 0;
            $irbpnr = 0;
            $iva12 = 0;
            $propina = 0;
            $valorTotal = 0;

            $idProductoArray = $request->request->get("idProducto");
            if ($idProductoArray == null) {
                $this->get('session')->getFlashBag()->add(
                        'notice', "La factura debe contener al menos un producto"
                );
                return $this->redirect($this->generateUrl('factura_new', array()));
            }
            $productos = $em->getRepository('FactelBundle:Producto')->findById($idProductoArray);
            if (count($productos) == 0) {
                $this->get('session')->getFlashBag()->add(
                        'notice', "Los productos solicitados para esta factura no se encuentran disponibles"
                );
                return $this->redirect($this->generateUrl('factura_new', array()));
            }
            foreach ($productos as $producto) {
                $facturaHasProducto = new FacturaHasProducto();
                $idProducto = $producto->getId();

                $facturaHasProducto->setProducto($producto);
                $impuestoIva = $producto->getImpuestoIVA();
                $impuestoICE = $producto->getImpuestoICE();
                $impuestoIRBPNR = $producto->getImpuestoIRBPNR();

                $cantidadArray = $request->request->get("cantidad");
                $descuentoArray = $request->request->get("descuento");
                $precioUnitario = $request->request->get("precio");
                $nombreProducto = $request->request->get("nombreProducto");
                $codigoProducto = $request->request->get("codigoProducto");
                $iceArray = $request->request->get("ice");
                $irbpnrArray = $request->request->get("irbpnr");
                $baseImponible = 0;



                if ($impuestoIva != null) {
                    $impuesto = new Impuesto();
                    $impuesto->setCodigo("2");
                    $impuesto->setCodigoPorcentaje($impuestoIva->getCodigoPorcentaje());
                    $baseImponible = floatval($cantidadArray[$idProducto]) * floatval($precioUnitario[$idProducto]) - floatval($descuentoArray[$idProducto]);
                    $impuesto->setBaseImponible($baseImponible);


                    $impuesto->setTarifa("0");
                    $impuesto->setValor(0.00);

                    if ($impuestoIva->getCodigoPorcentaje() == "0") {
                        $subTotal0 += $baseImponible;
                    } else if ($impuestoIva->getCodigoPorcentaje() == "6") {
                        $subTotaNoObjeto += $baseImponible;
                    } else if ($impuestoIva->getCodigoPorcentaje() == "7") {
                        $subTotaExento += $baseImponible;
                    } else {
                        $impuesto->setTarifa($impuestoIva->getTarifa());
                        $impuesto->setValor(round($baseImponible * $impuestoIva->getTarifa() / 100, 2));

                        $subTotal12 += $baseImponible;
                        $iva12 += round($baseImponible * $impuestoIva->getTarifa() / 100, 2);
                    }

                    $impuesto->setFacturaHasProducto($facturaHasProducto);

                    $facturaHasProducto->addImpuesto($impuesto);
                    $subTotalSinImpuesto += $baseImponible;
                }
                if ($impuestoICE != null) {
                    $impuesto = new Impuesto();
                    $impuesto->setCodigo("3");
                    $impuesto->setCodigoPorcentaje($impuestoICE->getCodigoPorcentaje());
                    $impuesto->setTarifa("0");
                    $impuesto->setBaseImponible($baseImponible);
                    $impuesto->setValor($iceArray[$idProducto]);

                    $impuesto->setFacturaHasProducto($facturaHasProducto);

                    $facturaHasProducto->addImpuesto($impuesto);
                    $ice += floatval($iceArray[$idProducto]);
                }

                if ($impuestoIRBPNR != null) {
                    $impuesto = new Impuesto();
                    $impuesto->setCodigo("5");
                    $impuesto->setCodigoPorcentaje($impuestoIRBPNR->getCodigoPorcentaje());
                    $impuesto->setTarifa("0");
                    $impuesto->setBaseImponible($baseImponible);
                    $impuesto->setValor($irbpnrArray[$idProducto]);

                    $impuesto->setFacturaHasProducto($facturaHasProducto);

                    $facturaHasProducto->addImpuesto($impuesto);
                    $irbpnr += floatval($irbpnrArray[$idProducto]);
                }
                $descuento += floatval($descuentoArray[$idProducto]);

                $facturaHasProducto->setCantidad($cantidadArray[$idProducto]);
                $facturaHasProducto->setPrecioUnitario($precioUnitario[$idProducto]);
                $facturaHasProducto->setDescuento($descuentoArray[$idProducto]);
                $facturaHasProducto->setValorTotal($baseImponible);
                $facturaHasProducto->setNombre($nombreProducto[$idProducto]);
                $facturaHasProducto->setCodigoProducto($codigoProducto[$idProducto]);
                $facturaHasProducto->setFactura($entity);
                $entity->addFacturasHasProducto($facturaHasProducto);
            }
            $entity->setFormaPago($formaPago);
            $entity->setTotalSinImpuestos($subTotalSinImpuesto);
            $entity->setSubtotal12($subTotal12);
            $entity->setSubtotal0($subTotal0);
            $entity->setSubtotalNoIVA($subTotaNoObjeto);
            $entity->setSubtotalExentoIVA($subTotaExento);
            $entity->setValorICE($ice);
            $entity->setValorIRBPNR($irbpnr);
            $entity->setIva12($iva12);
            $entity->setTotalDescuento($descuento);
            $entity->setPropina(0);
            $importeTotal = floatval($subTotalSinImpuesto) + floatval($ice) + floatval($irbpnr) + $iva12;
            $entity->setValorTotal($importeTotal);
            $em->persist($entity);
            $em->flush();
            if ($idFactura == null || $idFactura == '') {
                $ptoEmision[0]->setSecuencialFactura($ptoEmision[0]->getSecuencialFactura() + 1);
                $em->persist($ptoEmision[0]);
                $em->flush();
            }
            return $this->redirect($this->generateUrl('factura_show', array('id' => $entity->getId())));
        } else {
            throw $this->createNotFoundException('El usuario del sistema no tiene asignado un Punto de Emision.');
        }
    }

    /**
     * Creates a new Factura entity.
     *
     * @Route("/descargar/{id}/{type}", name="factura_descargar")
     * @Method("GET")
     * @Secure(roles="ROLE_EMISOR")
     */
    public function descargarAction($id, $type = "zip") {
        $em = $this->getDoctrine()->getManager();
        $factura = new Factura();
        $factura = $em->getRepository('FactelBundle:Factura')->findFacturaById($id);
        if ($factura->getEstado() != "AUTORIZADO") {
            $this->get('session')->getFlashBag()->add(
                    'notice', "Para descargar los archivos generados el comprobantes debe haber sido AUTORIZADO"
            );
            return $this->redirect($this->generateUrl('factura_show', array('id' => $factura->getId())));
        }
        $archivoName = $factura->getNombreArchivo();
        $pathXML = $factura->getEmisor()->getDirDocAutorizados() . DIRECTORY_SEPARATOR . $factura->getCliente()->getIdentificacion() . DIRECTORY_SEPARATOR . $archivoName . ".xml";
        $pathPDF = $factura->getEmisor()->getDirDocAutorizados() . DIRECTORY_SEPARATOR . $factura->getCliente()->getIdentificacion() . DIRECTORY_SEPARATOR . $archivoName . ".pdf";
        if ($type == "zip") {
            $zip = new \ZipArchive();
            $zipDir = "../web/zip/" . $archivoName . '.zip';
            $zip->open($zipDir, \ZipArchive::CREATE);

            if (file_exists($pathXML)) {
                $zip->addFromString(basename($pathXML), file_get_contents($pathXML));
            }
            if (file_exists($pathPDF)) {
                $zip->addFromString(basename($pathPDF), file_get_contents($pathPDF));
            }

            $zip->close();
            $response = new Response();
            //then send the headers to foce download the zip file
            $response->headers->set('Content-Type', 'application/zip');
            $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($zipDir) . '"');
            $response->headers->set('Pragma', "no-cache");
            $response->headers->set('Expires', "0");
            $response->headers->set('Content-Transfer-Encoding', "binary");
            $response->sendHeaders();
            $response->setContent(readfile($zipDir));
            return $response;
        } else if ($type == "pdf") {
            $response = new Response();
            //then send the headers to foce download the zip file
            $response->headers->set('Content-Type', 'application/pdf');
            $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($pathPDF) . '"');
            $response->headers->set('Pragma', "no-cache");
            $response->headers->set('Expires', "0");
            $response->headers->set('Content-Transfer-Encoding', "binary");
            $response->sendHeaders();
            $response->setContent(readfile($pathPDF));
            return $response;
        }
    }

    /**
     * Displays a form to create a new Factura entity.
     *
     * @Route("/nueva", name="factura_new")
     * @Method("GET")
     * @Secure(roles="ROLE_EMISOR")
     * @Template()
     */
    public function newAction() {
        $em = $this->getDoctrine()->getManager();
        $ptoEmision = $em->getRepository('FactelBundle:PtoEmision')->findPtoEmisionEstabEmisorByUsuario($this->get("security.context")->gettoken()->getuser()->getId());
        if ($ptoEmision != null && count($ptoEmision) > 0) {
            return array(
                'pto' => $ptoEmision,
            );
        } else {
            throw $this->createNotFoundException('El usuario del sistema no tiene asignado un Punto de Emision.');
        }
    }

    /**
     * Finds and displays a Factura entity.
     *
     * @Route("/{id}", name="factura_show")
     * @Method("GET")
     * @Secure(roles="ROLE_EMISOR")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FactelBundle:Factura')->findFacturaById($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Factura entity.');
        }

        return array(
            'entity' => $entity,
        );
    }

    /**
     * Displays a form to edit an existing Factura entity.
     *
     * @Route("/{id}/editar", name="factura_edit")
     * @Method("GET")
     * @Secure(roles="ROLE_EMISOR")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FactelBundle:Factura')->findFacturaById($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Factura entity.');
        }
        if ($entity->getEstado() == "AUTORIZADO" || $entity->getEstado() == "ERROR" || $entity->getEstado() == "PROCESANDOSE") {
            $this->get('session')->getFlashBag()->add(
                    'notice', "Solo puede ser editada la factura en estado: NO AUTORIZADO Y DEVUELTA"
            );
            return $this->redirect($this->generateUrl('factura_show', array('id' => $entity->getId())));
        }
        return array(
            'entity' => $entity,
        );
    }

    /**
     * Creates a form to edit a Factura entity.
     *
     * @param Factura $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Factura $entity) {
        $form = $this->createForm(new FacturaType(), $entity, array(
            'action' => $this->generateUrl('factura_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Deletes a Factura entity.
     *
     * @Route("/{id}", name="factura_delete")
     * @Secure(roles="ROLE_EMISOR")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FactelBundle:Factura')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Factura entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('factura'));
    }

    /**
     * Creates a form to delete a Factura entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('factura_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

    private function claveAcceso($factura, $emisor, $establecimiento, $ptoEmision, $fechaEmision) {
        $claveAcceso = str_replace("/", "", $fechaEmision);
        $claveAcceso .= "01";
        $claveAcceso .= $emisor->getRuc();
        $claveAcceso .= $factura->getAmbiente();
        $serie = $establecimiento->getCodigo() . $ptoEmision->getCodigo();
        $claveAcceso .= $serie;
        $claveAcceso .= $factura->getSecuencial();
        $claveAcceso .= "12345678";
        $claveAcceso .= $factura->getTipoEmision();
        $claveAcceso .= $this->modulo11($claveAcceso);

        return $claveAcceso;
    }

    private function modulo11($claveAcceso) {
        $multiplos = [2, 3, 4, 5, 6, 7];
        $i = 0;
        $cantidad = strlen($claveAcceso);
        $total = 0;
        while ($cantidad > 0) {
            $total += intval(substr($claveAcceso, $cantidad - 1, 1)) * $multiplos[$i];
            $i++;
            $i = $i % 6;
            $cantidad--;
        }
        $modulo11 = 11 - $total % 11;
        if ($modulo11 == 11) {
            $modulo11 = 0;
        } else if ($modulo11 == 10) {
            $modulo11 = 1;
        }

        return strval($modulo11);
    }

}
