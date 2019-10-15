<?php

namespace FactelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plancuentas
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FactelBundle\Entity\PlancuentasRepository")
 */
class Plancuentas
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="idempresa", type="integer")
     */
    private $idempresa;

    /**
     * @var string
     *
     * @ORM\Column(name="codCta", type="string", length=50)
     */
    private $codCta;

    /**
     * @ORM\ManyToOne(targetEntity="Tipocuenta", inversedBy="planes")
     * @ORM\JoinColumn(name="tipoCta", referencedColumnName="id", nullable=false)
     */
    private $tipoCta;
    

    /**
     * @ORM\ManyToOne(targetEntity="Moneda", inversedBy="planes")
     * @ORM\JoinColumn(name="codMoneda", referencedColumnName="id", nullable=false)
     */
    private $codMoneda;

    /**
     * @var string
     *
     * @ORM\Column(name="flujoCaja", type="string", length=100)
     */
    private $flujoCaja;

    /**
     * @var string
     *
     * @ORM\Column(name="bansel", type="string", length=50)
     */
    private $bansel;

    /**
     * @ORM\ManyToOne(targetEntity="Grupo", inversedBy="planes")
     * @ORM\JoinColumn(name="grupo", referencedColumnName="id", nullable=false)
     */
    private $grupo;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="planes")
     * @ORM\JoinColumn(name="codUsu", referencedColumnName="id", nullable=false)
     */
    private $codUsu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaCreacion", type="date")
     */
    private $fechaCreacion;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idempresa
     *
     * @param integer $idempresa
     *
     * @return Plancuentas
     */
    public function setIdempresa($idempresa)
    {
        $this->idempresa = $idempresa;

        return $this;
    }

    /**
     * Get idempresa
     *
     * @return integer
     */
    public function getIdempresa()
    {
        return $this->idempresa;
    }

    /**
     * Set codCta
     *
     * @param string $codCta
     *
     * @return Plancuentas
     */
    public function setCodCta($codCta)
    {
        $this->codCta = $codCta;

        return $this;
    }

    /**
     * Get codCta
     *
     * @return string
     */
    public function getCodCta()
    {
        return $this->codCta;
    }

   
    /**
     * Set tipoCta
     *
     * @param \FactelBundle\Entity\Tipocuenta $tipoCta
     * @return Plancuentas
     */
    public function setTipoCta(\FactelBundle\Entity\Tipocuenta $tipoCta) {
        $this->tipoCta = $tipoCta;

        return $this;
    }

    /**
     * Get tipoCta
     *
     * @return \FactelBundle\Entity\Tipocuenta 
     */
    public function getTipoCta() {
        return $this->tipoCta;
    }

    /**
     * Set codMoneda
     *
     * @param \FactelBundle\Entity\Moneda $codMoneda
     * @return Plancuentas
     */
    public function setCodMoneda(\FactelBundle\Entity\Moneda $codMoneda) {
        $this->codMoneda = $codMoneda;

        return $this;
    }

    /**
     * Get codMoneda
     *
     * @return \FactelBundle\Entity\Moneda 
     */
    public function getCodMoneda() {
        return $this->codMoneda;
    }

    /**
     * Set flujoCaja
     *
     * @param string $flujoCaja
     *
     * @return Plancuentas
     */
    public function setFlujoCaja($flujoCaja)
    {
        $this->flujoCaja = $flujoCaja;

        return $this;
    }

    /**
     * Get flujoCaja
     *
     * @return string
     */
    public function getFlujoCaja()
    {
        return $this->flujoCaja;
    }

    /**
     * Set bansel
     *
     * @param string $bansel
     *
     * @return Plancuentas
     */
    public function setBansel($bansel)
    {
        $this->bansel = $bansel;

        return $this;
    }

    /**
     * Get bansel
     *
     * @return string
     */
    public function getBansel()
    {
        return $this->bansel;
    }

    /**
     * Set grupo
     *
     * @param \FactelBundle\Entity\Grupo $grupo
     * @return Plancuentas
     */
    public function setGrupo(\FactelBundle\Entity\Grupo $grupo) {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return \FactelBundle\Entity\Grupo 
     */
    public function getGrupo() {
        return $this->grupo;
    }

    /**
     * Set codUsu
     *
     * @param \FactelBundle\Entity\User $codUsu
     * @return Plancuentas
     */
    public function setCodUsu(\FactelBundle\Entity\User $codUsu) {
        $this->codUsu = $codUsu;

        return $this;
    }

    /**
     * Get codUsu
     *
     * @return \FactelBundle\Entity\User 
     */
    public function getCodUsu() {
        return $this->codUsu;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Plancuentas
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }
}

