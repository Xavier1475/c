<?php

namespace FactelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Grupo
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Grupo
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
     * @ORM\Column(name="nombGrupo", type="string", length=50)
     */
    private $nombGrupo;

    /**
     * @ORM\OneToMany(targetEntity="Plancuentas", mappedBy="grupo")
     */
    protected $planes;

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
     * @return Grupo
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
     * Set nombGrupo
     *
     * @param string $nombGrupo
     *
     * @return Grupo
     */
    public function setNombGrupo($nombGrupo)
    {
        $this->nombGrupo = $nombGrupo;

        return $this;
    }

    /**
     * Get nombGrupo
     *
     * @return string
     */
    public function getNombGrupo()
    {
        return $this->nombGrupo;
    }
}

