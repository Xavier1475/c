<?php

namespace FactelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
/**
 * Tipocuenta
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="FactelBundle\Entity\TipocuentaRepository")
 */
class Tipocuenta
{
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
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombreCuenta", type="string", length=100)
     */
    private $nombreCuenta;

    /**
     * @ORM\OneToMany(targetEntity="Plancuentas", mappedBy="tipocuenta")
     */
    private $planes;

    /**
     * Constructor
     */
    public function __construct() {
        $this->planes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add planes
     *
     * @param \FactelBundle\Entity\Plancuentas $planes
     * @return Tipocuenta
     */
    public function addPlanes(\FactelBundle\Entity\Plancuentas $planes) {
        $this->planes[] = $planes;

        return $this;
    }

    /**
     * Remove planes
     *
     * @param \FactelBundle\Entity\Plancuentas $planes
     */
    public function removePlanes(\FactelBundle\Entity\Plancuentas $planes) {
        $this->planes->removeElement($planes);
    }

    /**
     * Get planes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPlanes() {
        return $this->planes;
    }


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
     * Set nombreCuenta
     *
     * @param string $nombreCuenta
     *
     * @return Tipocuenta
     */
    public function setNombreCuenta($nombreCuenta)
    {
        $this->nombreCuenta = $nombreCuenta;

        return $this;
    }

    /**
     * Get nombreCuenta
     *
     * @return string
     */
    public function getNombreCuenta()
    {
        return $this->nombreCuenta;
    }
}

