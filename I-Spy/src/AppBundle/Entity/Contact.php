<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contact
 *
 * @ORM\Table(name="contact", uniqueConstraints={@ORM\UniqueConstraint(name="unique_phone_idRef", columns={"idRef", "phone_id"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContactRepository")
 */
class Contact implements \JsonSerializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="numero", type="string", length=255)
     */
    private $numero;

    /**
     * @var int
     *
     * @ORM\Column(name="idRef", type="integer")
     */
    private $idRef;

    /**
     * @ORM\ManyToOne(targetEntity="Phone", inversedBy="contacts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $phone;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Contact
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set numero
     *
     * @param string $numero
     *
     * @return Contact
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set idRef
     *
     * @param integer $idRef
     *
     * @return Contact
     */
    public function setIdRef($idRef)
    {
        $this->idRef = $idRef;

        return $this;
    }

    /**
     * Get idRef
     *
     * @return int
     */
    public function getIdRef()
    {
        return $this->idRef;
    }

    /**
     * Set phone
     *
     * @param \AppBundle\Entity\Phone $phone
     *
     * @return Contact
     */
    public function setPhone(\AppBundle\Entity\Phone $phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return \AppBundle\Entity\Phone
     */
    public function getPhone()
    {
        return $this->phone;
    }

    public function jsonSerialize()
    {
        return array(
            'id' => $this->id,
            'nom' => $this->nom,
            'numero' => $this->numero,
            'idRef' => $this->idRef,
            'phone' => $this->phone->getId(),
        );
    }
}
