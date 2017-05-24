<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Phone
 *
 * @ORM\Table(name="phone")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PhoneRepository")
 */
class Phone implements \JsonSerializable
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
     * @ORM\Column(name="login", type="string", length=255, unique=true)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="PositionGPS", mappedBy="phone")
     */
    private $positionsGPS;


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
     * Set login
     *
     * @param string $login
     *
     * @return Phone
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Phone
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function jsonSerialize()
    {
        return array(
            'id' => $this->id,
            'login' => $this->login,
            'positionsGPS' => $this->getPositionsGPS()->toArray(),
        );
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->positionsGPS = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add positionGPS
     *
     * @param \AppBundle\Entity\PositionGPS $positionGPS
     *
     * @return Phone
     */
    public function addPositionGPS(\AppBundle\Entity\PositionGPS $positionGPS)
    {
        $this->positionsGPS[] = $positionGPS;

        return $this;
    }

    /**
     * Remove positionGPS
     *
     * @param \AppBundle\Entity\PositionGPS $positionsGPS
     */
    public function removePositionGPS(\AppBundle\Entity\PositionGPS $positionGPS)
    {
        $this->positionsGPS->removeElement($positionGPS);
    }

    /**
     * Get positionsGPS
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPositionsGPS()
    {
        return $this->positionsGPS;
    }
}
