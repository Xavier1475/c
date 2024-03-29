<?php

namespace FactelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Factura
 * @ORM\Entity(repositoryClass="FactelBundle\Entity\Repository\FacturaRepository")
 */
class Factura {

    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Blameable\Blameable
    ;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="claveAcceso", type="string", length=49)
     */
    protected $claveAcceso;

    /**
     * @var string
     *
     * @ORM\Column(name="numeroAutorizacion", type="string", length=49, nullable=true)
     */
    protected $numeroAutorizacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaAutorizacion", type="datetime", nullable=true)
     */
    protected $fechaAutorizacion;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=100)
     */
    protected $estado;

    /**
     * @var string
     *
     * @ORM\Column(name="ambiente", type="string", length=1)
     */
    protected $ambiente;

    /**
     * @var string
     *
     * @ORM\Column(name="tipoEmision", type="string", length=100)
     */
    protected $tipoEmision;

    /**
     * @var string
     *
     * @ORM\Column(name="secuencial", type="string", length=9)
     */
    protected $secuencial;

    /**
     * @var string
     *
     * @ORM\Column(name="formaPago", type="string", length=4)
     */
    protected $formaPago;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaEmision", type="date")
     */
    protected $fechaEmision;

    /**
     * @var string
     *
     * @ORM\Column(name="nombreArchivo", type="string", length=200, nullable=true)
     */
    protected $nombreArchivo;

    /**
     * @ORM\ManyToOne(targetEntity="Cliente", inversedBy="facturas")
     * @ORM\JoinColumn(name="cliente_id", referencedColumnName="id", nullable=false)
     */
    protected $cliente;

    /**
     * @ORM\ManyToOne(targetEntity="Emisor", inversedBy="facturas")
     * @ORM\JoinColumn(name="emisor_id", referencedColumnName="id", nullable=false)
     */
    protected $emisor;

    /**
     * @ORM\ManyToOne(targetEntity="Establecimiento", inversedBy="facturas")
     * @ORM\JoinColumn(name="establecimiento_id", referencedColumnName="id", nullable=false)
     */
    protected $establecimiento;

    /**
     * @ORM\ManyToOne(targetEntity="PtoEmision", inversedBy="facturas")
     * @ORM\JoinColumn(name="ptoEmision_id", referencedColumnName="id", nullable=false)
     */
    protected $ptoEmision;

    /**
     * @ORM\OneToMany(targetEntity="CampoAdicional", mappedBy="factura", cascade={"persist"})
     */
    protected $composAdic;

    /**
     * @ORM\OneToMany(targetEntity="FacturaHasProducto", mappedBy="factura", cascade={"persist"})
     */
    protected $facturasHasProducto;

    /**
     * @ORM\OneToMany(targetEntity="Mensaje", mappedBy="factura")
     */
    protected $mensajes;

    /**
     * @var decimal
     *
     * @ORM\Column(name="totalSinImpuestos", type="decimal", scale=2)
     */
    protected $totalSinImpuestos;

    /**
     * @var decimal
     *
     * @ORM\Column(name="subtotal12", type="decimal", scale=2)
     */
    protected $subtotal12;

    /**
     * @var decimal
     *
     * @ORM\Column(name="subtotal0", type="decimal", scale=2)
     */
    protected $subtotal0;

    /**
     * @var float
     *
     * @ORM\Column(name="subtotalNoIVA", type="decimal", scale=2)
     */
    protected $subtotalNoIVA;

    /**
     * @var float
     *
     * @ORM\Column(name="subtotalExentoIVA", type="decimal", scale=2)
     */
    protected $subtotalExentoIVA;

    /**
     * @var float
     *
     * @ORM\Column(name="valorICE", type="decimal", scale=2)
     */
    protected $valorICE;

    /**
     * @var float
     *
     * @ORM\Column(name="valorIRBPNR", type="decimal", scale=2)
     */
    protected $valorIRBPNR;

    /**
     * @var float
     *
     * @ORM\Column(name="iva12", type="decimal", scale=2)
     */
    protected $iva12;

    /**
     * @var float
     *
     * @ORM\Column(name="totalDescuento", type="decimal", scale=2)
     */
    protected $totalDescuento;

    /**
     * @var float
     *
     * @ORM\Column(name="propina", type="decimal", scale=2)
     */
    protected $propina;

    /**
     * @var float
     *
     * @ORM\Column(name="valorTotal", type="decimal", scale=2)
     */
    protected $valorTotal;

    /**
     * @var int
     *
     * @ORM\Column(name="nroCuota", type="integer", scale=2)
     */
    protected $nroCuota;
    
    /**
     * @var string
     *
     * @ORM\Column(name="banco", type="string", length=200, nullable=true)
     */
    protected $banco;

    /**
     * @var integer
     *
     * @ORM\Column(name="ctaContable", type="integer")
     */
    protected $ctaContable;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaVencimiento", type="date")
     */
    protected $fechaVencimiento;

    /**
     * @var integer
     *
     * @ORM\Column(name="nroCuenta", type="integer")
     */
    protected $nroCuenta;

    /**
     * @var float
     *
     * @ORM\Column(name="monto", type="decimal", scale=2)
     */
    protected $monto;

    /**
     * @var float
     *
     * @ORM\Column(name="abono", type="decimal", scale=2)
     */
    protected $abono;

    /**
     * @var float
     *
     * @ORM\Column(name="saldo", type="decimal", scale=2)
     */
    protected $saldo;

    /**
     * @var string
     *
     * @ORM\Column(name="estadoCuenta", type="string", length=30, nullable=true)
     */
    protected $estadoCuenta;

    /**
     * @ORM\Column(name="firmado", type="boolean")
     */
    private $firmado = false;
    
    /**
     * @ORM\Column(name="enviarSiAutorizado", type="boolean")
     */
    private $enviarSiAutorizado = false;
    
     /**
     * @var string
     *
     * @ORM\Column(name="observacion", type="string", length=300, nullable=TRUE)
     */
    protected $observacion;
    
    /**
     * Set totalSinImpuestos
     *
     * @param float $totalSinImpuestos
     * @return Factura
     */
   
    
    public function setTotalSinImpuestos($totalSinImpuestos) {
        $this->totalSinImpuestos = $totalSinImpuestos;

        return $this;
    }

    /**
     * Get totalSinImpuestos
     *
     * @return float 
     */
    public function getTotalSinImpuestos() {
        return $this->totalSinImpuestos;
    }

    /**
     * Set totalSinImpuestos
     *
     * @param float $totalSinImpuestos
     * @return Factura
     */
    public function setFormaPago($formaPago) {
        $this->formaPago = $formaPago;

        return $this;
    }

    /**
     * Get totalSinImpuestos
     *
     * @return float 
     */
    public function getFormaPago() {
        return $this->formaPago;
    }

    /**
     * Set totalDescuento
     *
     * @param float $totalDescuento
     * @return Factura
     */
    public function setTotalDescuento($totalDescuento) {
        $this->totalDescuento = $totalDescuento;

        return $this;
    }

    /**
     * Get totalDescuento
     *
     * @return float 
     */
    public function getTotalDescuento() {
        return $this->totalDescuento;
    }

    /**
     * Set propina
     *
     * @param float $propina
     * @return Factura
     */
    public function setPropina($propina) {
        $this->propina = $propina;

        return $this;
    }

    /**
     * Get propina
     *
     * @return float 
     */
    public function getPropina() {
        return $this->propina;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->facturasHasProducto = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mensajes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add facturasHasProducto
     *
     * @param \FactelBundle\Entity\FacturaHasProducto $facturasHasProducto
     * @return Factura
     */
    public function addFacturasHasProducto(\FactelBundle\Entity\FacturaHasProducto $facturasHasProducto) {
        $this->facturasHasProducto[] = $facturasHasProducto;

        return $this;
    }

    /**
     * Remove facturasHasProducto
     *
     * @param \FactelBundle\Entity\FacturaHasProducto $facturasHasProducto
     */
    public function removeFacturasHasProducto(\FactelBundle\Entity\FacturaHasProducto $facturasHasProducto) {
        $this->facturasHasProducto->removeElement($facturasHasProducto);
    }

    /**
     * Get facturasHasProducto
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFacturasHasProducto() {
        return $this->facturasHasProducto;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set claveAcceso
     *
     * @param string $claveAcceso
     * @return Factura
     */
    public function setClaveAcceso($claveAcceso) {
        $this->claveAcceso = $claveAcceso;

        return $this;
    }

    /**
     * Get claveAcceso
     *
     * @return string 
     */
    public function getClaveAcceso() {
        return $this->claveAcceso;
    }

    /**
     * Set estado
     *
     * @param string $estado
     * @return Factura
     */
    public function setEstado($estado) {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string 
     */
    public function getEstado() {
        return $this->estado;
    }

    /**
     * Set tipoEmision
     *
     * @param string $tipoEmision
     * @return Factura
     */
    public function setTipoEmision($tipoEmision) {
        $this->tipoEmision = $tipoEmision;

        return $this;
    }

    /**
     * Get tipoEmision
     *
     * @return string 
     */
    public function getTipoEmision() {
        return $this->tipoEmision;
    }

    /**
     * Set fechaEmision
     *
     * @param \DateTime $fechaEmision
     * @return Factura
     */
    public function setFechaEmision($fechaEmision) {
        $this->fechaEmision = $fechaEmision;

        return $this;
    }

    /**
     * Get fechaEmision
     *
     * @return \DateTime 
     */
    public function getFechaEmision() {
        return $this->fechaEmision;
    }

    /**
     * Set emisor
     *
     * @param \FactelBundle\Entity\Emisor $emisor
     * @return Factura
     */
    public function setEmisor(\FactelBundle\Entity\Emisor $emisor) {
        $this->emisor = $emisor;

        return $this;
    }

    /**
     * Get emisor
     *
     * @return \FactelBundle\Entity\Emisor 
     */
    public function getEmisor() {
        return $this->emisor;
    }

    /**
     * Set establecimiento
     *
     * @param \FactelBundle\Entity\Establecimiento $establecimiento
     * @return Factura
     */
    public function setEstablecimiento(\FactelBundle\Entity\Establecimiento $establecimiento) {
        $this->establecimiento = $establecimiento;

        return $this;
    }

    /**
     * Get establecimiento
     *
     * @return \FactelBundle\Entity\Establecimiento 
     */
    public function getEstablecimiento() {
        return $this->establecimiento;
    }

    /**
     * Set ptoEmision
     *
     * @param \FactelBundle\Entity\PtoEmision $ptoEmision
     * @return Factura
     */
    public function setPtoEmision(\FactelBundle\Entity\PtoEmision $ptoEmision) {
        $this->ptoEmision = $ptoEmision;

        return $this;
    }

    /**
     * Get ptoEmision
     *
     * @return \FactelBundle\Entity\PtoEmision 
     */
    public function getPtoEmision() {
        return $this->ptoEmision;
    }

    /**
     * Add composAdic
     *
     * @param \FactelBundle\Entity\CampoAdicional $composAdic
     * @return Factura
     */
    public function addComposAdic(\FactelBundle\Entity\CampoAdicional $composAdic) {
        $this->composAdic[] = $composAdic;

        return $this;
    }

    /**
     * Remove composAdic
     *
     * @param \FactelBundle\Entity\CampoAdicional $composAdic
     */
    public function removeComposAdic(\FactelBundle\Entity\CampoAdicional $composAdic) {
        $this->composAdic->removeElement($composAdic);
    }

    /**
     * Get composAdic
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComposAdic() {
        return $this->composAdic;
    }

    /**
     * Set secuencial
     *
     * @param string $secuencial
     * @return Factura
     */
    public function setSecuencial($secuencial) {
        $this->secuencial = $secuencial;

        return $this;
    }

    /**
     * Get secuencial
     *
     * @return string 
     */
    public function getSecuencial() {
        return $this->secuencial;
    }

    /**
     * Set cliente
     *
     * @param \FactelBundle\Entity\Cliente $cliente
     * @return Factura
     */
    public function setCliente(\FactelBundle\Entity\Cliente $cliente) {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return \FactelBundle\Entity\Cliente 
     */
    public function getCliente() {
        return $this->cliente;
    }

    /**
     * Set subtotal12
     *
     * @param float $subtotal12
     * @return Factura
     */
    public function setSubtotal12($subtotal12) {
        $this->subtotal12 = $subtotal12;

        return $this;
    }

    /**
     * Get subtotal12
     *
     * @return float 
     */
    public function getSubtotal12() {
        return $this->subtotal12;
    }

    /**
     * Set subtotal0
     *
     * @param float $subtotal0
     * @return Factura
     */
    public function setSubtotal0($subtotal0) {
        $this->subtotal0 = $subtotal0;

        return $this;
    }

    /**
     * Get subtotal0
     *
     * @return float 
     */
    public function getSubtotal0() {
        return $this->subtotal0;
    }

    /**
     * Set subtotalNoIVA
     *
     * @param float $subtotalNoIVA
     * @return Factura
     */
    public function setSubtotalNoIVA($subtotalNoIVA) {
        $this->subtotalNoIVA = $subtotalNoIVA;

        return $this;
    }

    /**
     * Get subtotalNoIVA
     *
     * @return float 
     */
    public function getSubtotalNoIVA() {
        return $this->subtotalNoIVA;
    }

    /**
     * Set subtotalExentoIVA
     *
     * @param float $subtotalExentoIVA
     * @return Factura
     */
    public function setSubtotalExentoIVA($subtotalExentoIVA) {
        $this->subtotalExentoIVA = $subtotalExentoIVA;

        return $this;
    }

    /**
     * Get subtotalExentoIVA
     *
     * @return float 
     */
    public function getSubtotalExentoIVA() {
        return $this->subtotalExentoIVA;
    }

    /**
     * Set valorICE
     *
     * @param float $valorICE
     * @return Factura
     */
    public function setValorICE($valorICE) {
        $this->valorICE = $valorICE;

        return $this;
    }

    /**
     * Get valorICE
     *
     * @return float 
     */
    public function getValorICE() {
        return $this->valorICE;
    }

    /**
     * Set valorIRBPNR
     *
     * @param float $valorIRBPNR
     * @return Factura
     */
    public function setValorIRBPNR($valorIRBPNR) {
        $this->valorIRBPNR = $valorIRBPNR;

        return $this;
    }

    /**
     * Get valorIRBPNR
     *
     * @return float 
     */
    public function getValorIRBPNR() {
        return $this->valorIRBPNR;
    }

    /**
     * Set iva12
     *
     * @param float $iva12
     * @return Factura
     */
    public function setIva12($iva12) {
        $this->iva12 = $iva12;

        return $this;
    }

    /**
     * Get iva12
     *
     * @return float 
     */
    public function getIva12() {
        return $this->iva12;
    }

    /**
     * Set valorTotal
     *
     * @param float $valorTotal
     * @return Factura
     */
    public function setValorTotal($valorTotal) {
        $this->valorTotal = $valorTotal;

        return $this;
    }


    /**
     * Get valorTotal
     *
     * @return float 
     */
    public function getValorTotal() {
        return $this->valorTotal;
    }

    /**
     * Set nroCuota
     *
     * @param int $nroCuota
     * @return Factura
     */
    public function setNroCuota($nroCuota) {
        $this->nroCuota = $nroCuota;

        return $this;
    }


    /**
     * Get nroCuota
     *
     * @return int 
     */
    public function getNroCuota() {
        return $this->nroCuota;
    }

    

    /**
     * Get banco
     *
     * @return string 
     */
    public function getBanco() {
        return $this->banco;
    }

    /**
     * Set banco
     *
     * @param string $banco
     * @return Factura
     */
    public function setBanco($banco) {
        $this->banco = $banco;

        return $this;
    }

    /**
     * Set fechaVencimiento
     *
     * @param \DateTime $fechaVencimiento
     * @return Factura
     */
    public function setFechaVencimiento($fechaVencimiento) {
        $this->fechaVencimiento = $fechaVencimiento;

        return $this;
    }

    /**
     * Get fechaVencimiento
     *
     * @return \DateTime 
     */
    public function getFechaVencimiento() {
        return $this->fechaVencimiento;
    }

    /**
     * Set ctaContable
     *
     * @param integer $ctaContable
     * @return Factura
     */
    public function setCtaContable($ctaContable) {
        $this->ctaContable = $ctaContable;

        return $this;
    }

    /**
     * Get ctaContable
     *
     * @return integer 
     */
    public function getCtaContable() {
        return $this->ctaContable;
    }

    /**
     * Set nroCuenta
     *
     * @param integer $nroCuenta
     * @return Factura
     */
    public function setNroCuenta($nroCuenta) {
        $this->nroCuenta = $nroCuenta;

        return $this;
    }

    /**
     * Get nroCuenta
     *
     * @return integer 
     */
    public function getNroCuenta() {
        return $this->nroCuenta;
    }
    
    /**
     * Set monto
     *
     * @param float $monto
     * @return Factura
     */
    public function setMonto($monto) {
        $this->monto = $monto;

        return $this;
    }
    

    /**
     * Get monto
     *
     * @return float 
     */
    public function getMonto() {
        return $this->monto;
    }

    /**
     * Set abono
     *
     * @param float $abono
     * @return Factura
     */
    public function setAbono($abono) {
        $this->abono = $abono;

        return $this;
    }
    

    /**
     * Get abono
     *
     * @return float 
     */
    public function getAbono() {
        return $this->abono;
    }

    /**
     * Set saldo
     *
     * @param float $saldo
     * @return Factura
     */
    public function setSaldo($saldo) {
        $this->saldo = $saldo;

        return $this;
    }
    

    /**
     * Get saldo
     *
     * @return float 
     */
    public function getSaldo() {
        return $this->saldo;
    }
    /**
     * Set estadoCuenta
     *
     * @param string $estadoCuenta
     * @return Factura
     */
    public function setEstadoCuenta($estadoCuenta) {
        $this->estadoCuenta = $estadoCuenta;

        return $this;
    }
    

    /**
     * Get estadoCuenta 
     *
     * @return string 
     */
    public function getEstadoCuenta() {
        return $this->estadoCuenta;
    }

    /**
     * Set ambiente
     *
     * @param string $ambiente
     * @return Factura
     */
    public function setAmbiente($ambiente) {
        $this->ambiente = $ambiente;

        return $this;
    }

    /**
     * Get ambiente
     *
     * @return string 
     */
    public function getAmbiente() {
        return $this->ambiente;
    }

    /**
     * Set numeroAutorizacion
     *
     * @param string $numeroAutorizacion
     * @return Factura
     */
    public function setNumeroAutorizacion($numeroAutorizacion) {
        $this->numeroAutorizacion = $numeroAutorizacion;

        return $this;
    }

    /**
     * Get numeroAutorizacion
     *
     * @return string 
     */
    public function getNumeroAutorizacion() {
        return $this->numeroAutorizacion;
    }

    /**
     * Add mensajes
     *
     * @param \FactelBundle\Entity\Mensaje $mensajes
     * @return Factura
     */
    public function addMensaje(\FactelBundle\Entity\Mensaje $mensajes) {
        $this->mensajes[] = $mensajes;

        return $this;
    }

    /**
     * Remove mensajes
     *
     * @param \FactelBundle\Entity\Mensaje $mensajes
     */
    public function removeMensaje(\FactelBundle\Entity\Mensaje $mensajes) {
        $this->mensajes->removeElement($mensajes);
    }

    /**
     * Get mensajes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMensajes() {
        return $this->mensajes;
    }

    /**
     * Set fechaAutorizacion
     *
     * @param \dateTime $fechaAutorizacion
     * @return Factura
     */
    public function setFechaAutorizacion(\dateTime $fechaAutorizacion) {
        $this->fechaAutorizacion = $fechaAutorizacion;

        return $this;
    }

    /**
     * Get fechaAutorizacion
     *
     * @return \dateTime 
     */
    public function getFechaAutorizacion() {
        return $this->fechaAutorizacion;
    }

    /**
     * Set nombreArchivo
     *
     * @param string $nombreArchivo
     * @return Factura
     */
    public function setNombreArchivo($nombreArchivo) {
        $this->nombreArchivo = $nombreArchivo;

        return $this;
    }

    /**
     * Get nombreArchivo
     *
     * @return string 
     */
    public function getNombreArchivo() {
        return $this->nombreArchivo;
    }

    public function formaPagoStr() {
        $formaDePago = "";
        
        if ($this->formaPago == ("01")) {
            $formaDePago = "SIN UTILIZACION DEL SISTEMA FINANCIERO";
        } else if ($this->formaPago == ("15")) {
            $formaDePago = "COMPENSACIÓN DE DEUDAS";
        } else if ($this->formaPago == ("16")) {
            $formaDePago = "TARJETA DE DÉBITO";
        } else if ($this->formaPago == ("17")) {
            $formaDePago = "DINERO ELECTRÓNICO";
        } else if ($this->formaPago == ("18")) {
            $formaDePago = "TARJETA PREPAGO";
        }else if ($this->formaPago == ("19")) {
            $formaDePago = "TARJETA DE CRÉDITO";
        } else if ($this->formaPago == ("20")) {
            $formaDePago = "OTROS CON UTILIZACION DEL SISTEMA FINANCIERO";
        } else if ($this->formaPago == ("21")) {
            $formaDePago = ">ENDOSO DE TÍTULOS";
        }
        return $formaDePago;
    }


    /**
     * Set firmado
     *
     * @param boolean $firmado
     * @return Factura
     */
    public function setFirmado($firmado)
    {
        $this->firmado = $firmado;

        return $this;
    }

    /**
     * Get firmado
     *
     * @return boolean 
     */
    public function getFirmado()
    {
        return $this->firmado;
    }

    /**
     * Set enviarSiAutorizado
     *
     * @param boolean $enviarSiAutorizado
     * @return Factura
     */
    public function setEnviarSiAutorizado($enviarSiAutorizado)
    {
        $this->enviarSiAutorizado = $enviarSiAutorizado;

        return $this;
    }

    /**
     * Get enviarSiAutorizado
     *
     * @return boolean 
     */
    public function getEnviarSiAutorizado()
    {
        return $this->enviarSiAutorizado;
    }

    /**
     * Set observacion
     *
     * @param string $observacion
     *
     * @return Factura
     */
    public function setObservacion($observacion)
    {
        $this->observacion = $observacion;

        return $this;
    }

    /**
     * Get observacion
     *
     * @return string
     */
    public function getObservacion()
    {
        return $this->observacion;
    }
}
