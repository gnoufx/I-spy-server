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
     * @ORM\OrderBy({"datePosition" = "DESC"})
     */
    private $positionsGPS;

    /**
     * @ORM\OneToMany(targetEntity="Contact", mappedBy="phone")
     */
    private $contacts;


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

    /**
     * Add positionsGP
     *
     * @param \AppBundle\Entity\PositionGPS $positionsGP
     *
     * @return Phone
     */
    public function addPositionsGP(\AppBundle\Entity\PositionGPS $positionsGP)
    {
        $this->positionsGPS[] = $positionsGP;

        return $this;
    }

    /**
     * Remove positionsGP
     *
     * @param \AppBundle\Entity\PositionGPS $positionsGP
     */
    public function removePositionsGP(\AppBundle\Entity\PositionGPS $positionsGP)
    {
        $this->positionsGPS->removeElement($positionsGP);
    }

    /**
     * Add contact
     *
     * @param \AppBundle\Entity\Contact $contact
     *
     * @return Phone
     */
    public function addContact(\AppBundle\Entity\Contact $contact)
    {
        $this->contacts[] = $contact;

        return $this;
    }

    /**
     * Remove contact
     *
     * @param \AppBundle\Entity\Contact $contact
     */
    public function removeContact(\AppBundle\Entity\Contact $contact)
    {
        $this->contacts->removeElement($contact);
    }

    /**
     * Get contacts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContacts()
    {
        return $this->contacts;
    }
}
