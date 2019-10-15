<?php

namespace FactelBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


use FactelBundle\Entity\Factura;
use FactelBundle\Entity\Cliente;
use FactelBundle\Entity\FacturaHasProducto;
use FactelBundle\Entity\Impuesto;
use FactelBundle\Entity\CampoAdicional;
use FactelBundle\Form\FacturaType;
use FactelBundle\Form\ClienteType;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Response;
use FactelBundle\Util;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


require_once 'ProcesarComprobanteElectronico.php';
require_once 'reader.php';

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
     * @Route("/pagocli", name="pago")
     * @Secure(roles="ROLE_EMISOR")
     * @Method("GET")
     */
    public function indexPago() {
       /* $iDisplayStart="";
        $iDisplayLength="";
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
        */

        //return new Response($post_data, 200, array('Content-Type' => 'application/json'));
        
        $usu= new Factura();
        
        $form= $this->createFormBuilder($usu)
                    //->setAction($this->generateUrl('save_animal'))
                    ->add('cliente', 'entity',[
                        'class'=> 'FactelBundle:Cliente',
                        'property'=>'nombre',
                    ])
                    /*
                    ->add('nombre','entity',array(
                        'class'=> Cliente::class,
                        'placeholder'=>'',
                        'choices'=>$cliente
                    ))*/

                    ->add('formaPago', 'choice', array(
                        'label'=>'Formas de Pago',
                        'choices' => array(
                            '1'=>'Efectivo',
                            '2'=>'Deposito',
                            '3'=>'Cheque',
                            '4'=>'Transferencia',
                            '5'=>'Tarjeta de Credito',
                            '6'=>'Otro'
                    )))
                    ->add('secuencial', 'text', array(
                        'label' => 'Nro tarjeta',
                        'required' => true,
                        
                    )
                        
                    )
                    ->add('nroCuenta','text',array(
                        'label'=>'Nro Cuenta',
                        
                    ))
                    ->add('banco','text',array(
                        'label'=>'Banco',
                        'required' => true,
                    ))   

                    ->add('ctaContable','text',array(
                        'label'=>'Cuenta Contable',
                        'required' => true,
                    ))
                    ->add('ctaContable','text',array(
                        'label'=>'Cuenta Contable',
                        'required' => true,
                    ))
                    ->add('totalSinImpuestos', 'text', array(
                        'label'=>'Valor',
                        'required' => true,
                        'post_max_size_message'=>'6'
                        
                        )
                         
                    )
                    ->add('totalDescuento', 'text', array(
                        'label'=>'Descuento',
                        
                    ))
                    
                    ->getForm();
                    $dato=$form->getData();
                    $deleteForms = array();
                    /*$personas = $this->getDoctrine()
                    ->getRepository(Factura::class)
                    ->findAll();*/
                    //$r=$form->setData($dato['totalSinImpuestos']);
                    $personas = $this->getDoctrine()
                    ->getRepository(Factura::class)
                    ->findCuentas();
                        $fact= new Factura();
                        $s=$fact->getFechaVencimiento();
                    /*$fact = $this->getDoctrine()
                    ->getRepository(Factura::class)
                    ->findCuentas();
                    $sum=0;
                    foreach ($fact as $a) {
                        //$deleteForms[$entity->getId()] = $this->createDeleteForm($entity->getId())->createView();
                        $sum=0;
                        foreach($a->abono as $r){
                            $sum += $r->number;
                        }
                        $a->sum = $sum;
                    }*/

                return $this->render(
                        'FactelBundle:PagoCliente:index.html.twig',array(
                            'form' => $form->createView(),
                            'entities' => $personas,
                            'deleteForms' => $deleteForms,
                            
                        ));
    }
    
    /*
     * Lists all Factura entities.
     *
     * @Route("/pagocli/{sal}/sald", name="saldo")
     * @Secure(roles="ROLE_EMISOR")
     * @Method("GET")
     */
    public function saldoact($sal){
        $valor=$this->getDoctrine()
                    ->getRepository(Factura::class)
                    ->findSaldo($sal);

         return $this->render('FactelBundle:PagoCliente:index.html.twig',array(
             'valor'=>$valor
         ));           
    }
    /**
     * @Route("/pagocli/{id}/edit", name="form_edit")
     * @Secure(roles="ROLE_ADMIN, ROLE_EMISOR_ADMIN")
     * @Method("GET")
     * @Template()
     */
    public function editForm($id)
        
    {
        $valor=$this->getDoctrine()
                    ->getRepository(Factura::class)
                    ->findSaldo($id);

         return $this->render('FactelBundle:PagoCliente:edit.html.twig',array( 
             'valor'=>$valor
         ));
        /*
        $em = $this->getDoctrine()
        ->getRepository(Factura::class)->find($id);
        

        //$entity = $em->getRepository(Factura::class)->find($id);
        

        $editForm = $this->createEditForm($em);
        $deleteForm = $this->createDeleteForm($em);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
        $form = $this->createForm(FacturaType::class, $entity, array(
            'action' => $this->generateUrl('factura_edit', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;*/
        
    }
              
    
    /**
     * Lists all Factura entities.
     *
     * @Route("/pagocli/{id}/deleteform", name="flormu")
     * @Secure(roles="ROLE_ADMIN, ROLE_EMISOR_ADMIN")
     * @Method("GET")
     * @Template()
     */
    
    public function formularioPagos(Factura $id){
       /*$em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FactelBundle:Establecimiento')->findCuentasId($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Establecimiento entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render(
            'FactelBundle:PagoCliente:delete.html.twig', array(
            'entity' => $entity
            ,
            'delete_form' => $deleteForm->createView(),
        ));*/
        $doctrine = $this->getDoctrine();
        //Cargar entityManager
        $em = $doctrine->getManager();
        //CArgar el repositorio

        if($id && is_object($id)){
            $em->remove($id);
            $em->flush();
            $m="<script>alert('Cta Borrada exitosamente')</script>";

            
        }else{
            $m="<script>alert('No se pudo borrar la Cta')</script>";

        }
        
        
        return $this->redirect($this->generateUrl('pago'));
        

        }
 
    /**
     * Lists all Factura entities.
     *
     * @Route("/facturas", name="all_factura")
     * @Secure(roles="ROLE_EMISOR")
     * @Method("GET")
     */
    public function facturasAction() {
        $iDisplayStart="";
        $iDisplayLength="";
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
     *
     * @Route("/cargar", name="factura_create_masivo")
     * @Method("POST")
     * @Secure(roles="ROLE_EMISOR_ADMIN")
     */
    public function createFacturaMasivaAction(Request $request) {
        $form = $this->createFacturaMasivaForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $emisor = new \FactelBundle\Entity\Emisor();
            $ptoEmision = $em->getRepository('FactelBundle:PtoEmision')->findPtoEmisionEstabEmisorByUsuario($this->get("security.context")->gettoken()->getuser()->getId());
            $establecimiento = $ptoEmision[0]->getEstablecimiento();
            $emisor = $establecimiento->getEmisor();

            $newFile = $form['Facturas']->getData();
            $newFile->move($this->getUploadRootDir(), $newFile->getClientOriginalName());
            $data = new Spreadsheet_Excel_Reader();
            $data->Spreadsheet_Excel_Reader();
            $data->setOutputEncoding('CP1251');
            $productoCreado = 0;
            $productoActualizado = 0;

            $data->read($this->getUploadRootDir() . '/' . $newFile->getClientOriginalName());
            for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
                if (isset($data->sheets[0]['cells'][$i][1]) && isset($data->sheets[0]['cells'][$i][2]) && isset($data->sheets[0]['cells'][$i][3]) && isset($data->sheets[0]['cells'][$i][4]) && isset($data->sheets[0]['cells'][$i][5]) && isset($data->sheets[0]['cells'][$i][6]) && isset($data->sheets[0]['cells'][$i][7]) && isset($data->sheets[0]['cells'][$i][8])) {
                    $codigoPrincipal = $data->sheets[0]['cells'][$i][3];
                    $producto = $em->getRepository('FactelBundle:Producto')->findOneBy(array("codigoPrincipal" => $codigoPrincipal, "emisor" => $emisor->getId()));

                    $entity = new Factura();
                    $fechaEmision = date("d/m/Y");
                    $entity->setEstado("CREADA");
                    $entity->setAmbiente($emisor->getAmbiente());
                    $entity->setTipoEmision($emisor->getTipoEmision());
                    $secuencial = $ptoEmision[0]->getSecuencialFactura();
                    while (strlen($secuencial) < 9) {
                        $secuencial = "0" . $secuencial;
                    }
                    $entity->setSecuencial($secuencial);
                    $entity->setClaveAcceso($this->claveAcceso($entity, $emisor, $establecimiento, $ptoEmision[0], $fechaEmision));
                    $fechaModificada = str_replace("/", "-", $fechaEmision);
                    $fecha = new \DateTime($fechaModificada);
                    $entity->setFechaEmision($fecha);
                    $identificacion = $data->sheets[0]['cells'][$i][7];
                    $cliente = $em->getRepository('FactelBundle:Cliente')->findOneBy(array("identificacion" => $identificacion, "emisor" => $emisor->getId()));
                    if ($cliente == null) {
                        $cliente = new \FactelBundle\Entity\Cliente();
                        $cliente->setEmisor($emisor);
                    }
                    $cliente->setNombre($data->sheets[0]['cells'][$i][8]);
                    $cliente->setTipoIdentificacion($data->sheets[0]['cells'][$i][6]);
                    $cliente->setIdentificacion($identificacion);
                    if (isset($data->sheets[0]['cells'][$i][9])) {
                        $cliente->setCorreoElectronico($data->sheets[0]['cells'][$i][9]);
                    }
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
                    $entity->setFormaPago($data->sheets[0]['cells'][$i][2]);
                    $idFactura = $data->sheets[0]['cells'][$i][1];
                    $pos = 0;
                    $productosId = array();
                    $cantidadArray = array();
                    $descuentoArray = array();
                    $error = false;
                    while (true && isset($data->sheets[0]['cells'][$i][1])) {
                        if (isset($data->sheets[0]['cells'][$i][3]) && isset($data->sheets[0]['cells'][$i][4]) && isset($data->sheets[0]['cells'][$i][5])) {
                            if ($idFactura == $data->sheets[0]['cells'][$i][1]) {
                                $codPorducto = $data->sheets[0]['cells'][$i][3];
                                $productosId[$pos++] = $codPorducto;
                                $cantidadArray[$codPorducto] = $data->sheets[0]['cells'][$i][4];
                                $descuentoArray[$codPorducto] = $data->sheets[0]['cells'][$i][5];
                                $i++;
                            } else {
                                break;
                            }
                        } else {
                            $error = true;
                            break;
                        }
                    }
                    if ($error) {
                        break;
                    } else {
                        $i--;
                    }
                    $productos = $em->getRepository('FactelBundle:Producto')->findBy(array("codigoPrincipal" => $productosId));
                    foreach ($productos as $producto) {
                        $facturaHasProducto = new FacturaHasProducto();
                        $idProducto = $producto->getCodigoPrincipal();

                        $facturaHasProducto->setProducto($producto);
                        $impuestoIva = $producto->getImpuestoIVA();
                        $baseImponible = 0;
                        if ($impuestoIva != null) {
                            $impuesto = new Impuesto();
                            $impuesto->setCodigo("2");
                            $impuesto->setCodigoPorcentaje($impuestoIva->getCodigoPorcentaje());
                            $baseImponible = floatval($cantidadArray[$idProducto]) * floatval($producto->getPrecioUnitario()) - floatval($descuentoArray[$idProducto]);
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

                        $descuento += floatval($descuentoArray[$idProducto]);

                        $facturaHasProducto->setCantidad($cantidadArray[$idProducto]);
                        $facturaHasProducto->setPrecioUnitario($producto->getPrecioUnitario());
                        $facturaHasProducto->setDescuento($descuentoArray[$idProducto]);
                        $facturaHasProducto->setValorTotal($baseImponible);
                        $facturaHasProducto->setNombre($producto->getNombre());
                        $facturaHasProducto->setCodigoProducto($producto->getCodigoPrincipal());
                        $facturaHasProducto->setFactura($entity);
                        $entity->addFacturasHasProducto($facturaHasProducto);
                    }

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

                    $ptoEmision[0]->setSecuencialFactura($ptoEmision[0]->getSecuencialFactura() + 1);
                    $em->persist($ptoEmision[0]);
                    $em->flush();
                    
                    $this->funtionCrearXmlPDF($entity->getId());
                }
            }
        }
        return $this->redirect($this->generateUrl('factura'));
    }

    /**
     * Creates a new Factura entity.
     *
     * @Route("/colnar/{id}", name="factura_clonar")
     * @Method("GET")
     * @Secure(roles="ROLE_EMISOR")
     */
    public function clonarAccion($id) {
        $em = $this->getDoctrine()->getManager();
        $factura = new Factura();
        $entity = new Factura();
        $factura = $em->getRepository('FactelBundle:Factura')->findFacturaById($id);
        if (!$factura) {
            throw $this->createNotFoundException('No existe la factura con ID = ' + $id);
        }
        $fechaEmision = date("d/m/Y");
        $entity->setEstado("CREADA");
        $entity->setAmbiente($factura->getAmbiente());
        $entity->setTipoEmision($factura->getTipoEmision());
        $secuencial = $factura->getPtoEmision()->getSecuencialFactura();
        while (strlen($secuencial) < 9) {
            $secuencial = "0" . $secuencial;
        }
        $entity->setSecuencial($secuencial);
        $entity->setClaveAcceso($this->claveAcceso($entity, $factura->getEmisor(), $factura->getEstablecimiento(), $factura->getPtoEmision(), $fechaEmision));
        $entity->setObservacion($factura->getObservacion());
        $fechaModificada = str_replace("/", "-", $fechaEmision);
        $fecha = new \DateTime($fechaModificada);
        $entity->setFechaEmision($fecha);

        $entity->setCliente($factura->getCliente());
        $entity->setEmisor($factura->getEmisor());
        $entity->setEstablecimiento($factura->getEstablecimiento());
        $entity->setPtoEmision($factura->getPtoEmision());

        foreach ($factura->getFacturasHasProducto() as $factProducto) {
            $facturaHasProducto = new FacturaHasProducto();
            $producto = $factProducto->getProducto();
            $facturaHasProducto->setProducto($producto);
            $impuestoIva = $producto->getImpuestoIVA();
            $impuestoICE = $producto->getImpuestoICE();

            foreach ($factProducto->getImpuestos() as $factImpuesto) {
                $impuesto = new Impuesto();
                $impuesto->setCodigo($factImpuesto->getCodigo());
                $impuesto->setCodigoPorcentaje($factImpuesto->getCodigoPorcentaje());
                $impuesto->setBaseImponible($factImpuesto->getBaseImponible());
                $impuesto->setTarifa($factImpuesto->getTarifa());
                $impuesto->setValor($factImpuesto->getValor());
                $impuesto->setFacturaHasProducto($facturaHasProducto);
                $facturaHasProducto->addImpuesto($impuesto);
            }
            $facturaHasProducto->setCantidad($factProducto->getCantidad());
            $facturaHasProducto->setPrecioUnitario($factProducto->getPrecioUnitario());
            $facturaHasProducto->setDescuento($factProducto->getDescuento());
            $facturaHasProducto->setValorTotal($factProducto->getValorTotal());
            $facturaHasProducto->setNombre($factProducto->getNombre());
            $facturaHasProducto->setCodigoProducto($factProducto->getCodigoProducto());
            $facturaHasProducto->setFactura($entity);
            $entity->addFacturasHasProducto($facturaHasProducto);
        }
        $entity->setFormaPago($factura->getFormaPago());
        $entity->setTotalSinImpuestos($factura->getTotalSinImpuestos());
        $entity->setSubtotal12($factura->getSubtotal12());
        $entity->setSubtotal0($factura->getSubtotal0());
        $entity->setSubtotalNoIVA($factura->getSubtotalNoIVA());
        $entity->setSubtotalExentoIVA($factura->getSubtotalExentoIVA());
        $entity->setValorICE($factura->getValorICE());
        $entity->setValorIRBPNR($factura->getValorIRBPNR());
        $entity->setIva12($factura->getIva12());
        $entity->setTotalDescuento($factura->getTotalDescuento());
        $entity->setPropina(0);
        $entity->setValorTotal($factura->getValorTotal());
        $em->persist($entity);
        $em->flush();

        $factura->getPtoEmision()->setSecuencialFactura($factura->getPtoEmision()->getSecuencialFactura() + 1);
        $em->persist($factura->getPtoEmision());
        $em->flush();

        $this->funtionCrearXmlPDF($entity->getId());

        return $this->redirect($this->generateUrl('factura_show', array('id' => $entity->getId())));
    }

    /**
     * Creates a new Factura entity.
     *
     * @Route("/anular/{id}", name="factura_anular")
     * @Method("GET")
     * @Secure(roles="ROLE_EMISOR")
     */
    public function anularAccion($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FactelBundle:Factura')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('No existe la factura con ID = ' + $id);
        }
        $entity->setEstado("ANULADA");
        $em->persist($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('factura_show', array('id' => $entity->getId())));
    }

    public function funtionCrearXmlPDF($id) {
        $entity = new Factura();
        $procesarComprobanteElectronico = new \ProcesarComprobanteElectronico();
        $respuesta = null;
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FactelBundle:Factura')->findFacturaById($id);
        $emisor = $entity->getEmisor();
        $configApp = new \configAplicacion();
        $configApp->dirFirma = $emisor->getDirFirma();
        $configApp->passFirma = $emisor->getPassFirma();
        $configApp->dirAutorizados = $emisor->getDirDocAutorizados();

        if ($entity->getEstablecimiento()->getDirLogo() != "") {
            $configApp->dirLogo = $entity->getEstablecimiento()->getDirLogo();
        } else {
            $configApp->dirLogo = $emisor->getDirLogo();
        }
        $configCorreo = new \configCorreo();
        $configCorreo->correoAsunto = "Nuevo Comprobante Electrónico";
        $configCorreo->correoHost = $emisor->getServidorCorreo();
        $configCorreo->correoPass = $emisor->getPassCorreo();
        $configCorreo->correoPort = $emisor->getPuerto();
        $configCorreo->correoRemitente = $emisor->getCorreoRemitente();
        $configCorreo->sslHabilitado = $emisor->getSSL();


        $factura = new \factura();
        $factura->configAplicacion = $configApp;
        $factura->configCorreo = $configCorreo;

        $factura->ambiente = $entity->getAmbiente();
        $factura->tipoEmision = $entity->getTipoEmision();
        $factura->razonSocial = $emisor->getRazonSocial();
        if ($entity->getEstablecimiento()->getNombreComercial() != "") {
            $factura->nombreComercial = $entity->getEstablecimiento()->getNombreComercial();
        } else if ($emisor->getNombreComercial() != "") {
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
        $procesarComprobante->envioSRI = false;
        $respuesta = $procesarComprobanteElectronico->procesarComprobante($procesarComprobante);
        if ($respuesta->return->estadoComprobante == "FIRMADO") {
            $entity->setNombreArchivo("FAC" . $entity->getEstablecimiento()->getCodigo() . "-" . $entity->getPtoEmision()->getCodigo() . "-" . $entity->getSecuencial());
        }
        $em->persist($entity);
        $em->flush();
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
        $hoy = date("Y-m-d");
        if ($emisor->getPlan() != null && $emisor->getFechaFin()) {
            if ($hoy > $emisor->getFechaFin()) {
                $this->get('session')->getFlashBag()->add(
                        'notice', "Su plan ha caducado por fovor contacte con nuestro equipo para su renovacion"
                );
                return $this->redirect($this->generateUrl('factura_show', array('id' => $entity->getId())));
            }
            if ($emisor->getCantComprobante() > $emisor->getPlan()->getCantComprobante()) {
                $this->get('session')->getFlashBag()->add(
                        'notice', "Ha superado el numero de comprobantes contratado en su plan, por fovor contacte con nuestro equipo para su renovacion"
                );
                return $this->redirect($this->generateUrl('factura_show', array('id' => $entity->getId())));
            }
        }
        $configApp = new \configAplicacion();
        $configApp->dirFirma = $emisor->getDirFirma();
        $configApp->passFirma = $emisor->getPassFirma();
        $configApp->dirAutorizados = $emisor->getDirDocAutorizados();

        if ($entity->getEstablecimiento()->getDirLogo() != "") {
            $configApp->dirLogo = $entity->getEstablecimiento()->getDirLogo();
        } else {
            $configApp->dirLogo = $emisor->getDirLogo();
        }
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
            } else if ($emisor->getNombreComercial() != "") {
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
            } else if ($entity->getEstado() == "ERROR" || $entity->getEstado() == "CREADA") {
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
            if ($emisor->getAmbiente() == "2") {
                $emisor->setCantComprobante($emisor->getCantComprobante() + 1);
                $em->persist($emisor);
            }
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
        if ($destinatario != null && $destinatario != '') {
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
            $this->funtionCrearXmlPDF($entity->getId());  
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
     * @Route("/nuevo", name="factura_new")
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
     * @Route("/cargar", name="facturas_load")
     * @Method("GET")
     * @Secure(roles="ROLE_EMISOR_ADMIN")
     * @Template()
     */
    public function cargarFacturaAction() {
        $form = $this->createFacturaMasivaForm();

        return array(
            'form' => $form->createView(),
        );
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
        if ($entity->getEstado() == "AUTORIZADO" || $entity->getEstado() == "ERROR" ) {
            $this->get('session')->getFlashBag()->add(
                    'notice', "Solo puede ser editada la factura en estado: NO AUTORIZADO, DEVUELTA y PROCESANDOSE"
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
        $form = $this->createForm(FacturaType::class, $entity, array(
            'action' => $this->generateUrl('factura_edit', array('id' => $entity->getId())),
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
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FactelBundle:Factura')->findFacturaById($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Factura entity.');
            }

            $em->remove($entity);
            $em->flush();

        return $this->redirect($this->generateUrl('factura'));
    }

    /**
     * Creates a form to delete a Factura entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function eliminarhas($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('FactelBundle:Factura')->findhas($id);

        if (!$entity) {
                throw $this->createNotFoundException('Unable to find Factura entity.');
        }

        $em->remove($entity);
        $em->flush();
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

    public function getUploadRootDir() {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../web/upload';
    }

    public function createFacturaMasivaForm() {

        $builder = $this->createFormBuilder();
        $builder->setAction($this->generateUrl('factura_create_masivo'));
        $builder->setMethod('POST');

        $builder->add('Facturas', 'file');

        $builder->add('import', 'submit', array(
            'label' => 'Cargar',
            'attr' => array('class' => 'import'),
        ));
        return $builder->getForm();
    }

}
