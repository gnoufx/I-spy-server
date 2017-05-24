<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PositionGPS
 *
 * @ORM\Table(name="position_gps")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PositionGPSRepository")
 */
class PositionGPS implements \JsonSerializable
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
     * @var float
     *
     * @ORM\Column(name="latitude", type="float")
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     */
    private $longitude;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255)
     */
    private $pays;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="codePostal", type="string", length=255)
     */
    private $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePosition", type="datetime")
     */
    private $datePosition;

    /**
     * @ORM\ManyToOne(targetEntity="Phone", inversedBy="positionsGPS")
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
     * Set latitude
     *
     * @param float $latitude
     *
     * @return PositionGPS
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return PositionGPS
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return PositionGPS
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return PositionGPS
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set codePostal
     *
     * @param string $codePostal
     *
     * @return PositionGPS
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return string
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return PositionGPS
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set datePosition
     *
     * @param \DateTime $datePosition
     *
     * @return PositionGPS
     */
    public function setDatePosition($datePosition)
    {
        $this->datePosition = $datePosition;

        return $this;
    }

    /**
     * Get datePosition
     *
     * @return \DateTime
     */
    public function getDatePosition()
    {
        return $this->datePosition;
    }

    /**
     * Set phone
     *
     * @param \AppBundle\Entity\Phone $phone
     *
     * @return PositionGPS
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
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'pays' => $this->pays,
            'ville' => $this->ville,
            'codePostal' => $this->codePostal,
            'adresse' => $this->adresse,
            'datePosition' => $this->datePosition->format("Y-m-d H:i:s"),
            'phone' => $this->phone->getId(),
        );
    }
}
