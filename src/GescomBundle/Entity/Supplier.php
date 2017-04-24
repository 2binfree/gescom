<?php

namespace GescomBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Supplier
 *
 * @ORM\Table(name="supplier")
 * @ORM\Entity(repositoryClass="GescomBundle\Repository\SupplierRepository")
 */
class Supplier
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
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=100)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="mail", type="string", length=100)
     * @Assert\Email()
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="postalCode", type="string", length=5)
     * @Assert\Regex("/[0-9]{5}/")
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="town", type="string", length=50)
     * @Assert\Regex("/[A-Za-z \-']+/")
     */
    private $town;

    /**
     * @var string
     *
     * @ORM\Column(name="siret", type="string", length=20)
     * @Assert\Regex("/[0-9]{3}[ \.\-]?[0-9]{3}[ \.\-]?[0-9]{3}[ \.\-]?[0-9]{5}/")
     */
    private $siret;

    /**
     * @var string
     *
     * @ORM\Column(name="web", type="string", length=255)
     * @Assert\Url()
     */
    private $web;

    /**
     * @var int
     *
     * @ORM\Column(name="deliveryTime", type="integer")
     * @Assert\Regex("/^[0-3]?[0-9]/")
     */
    private $deliveryTime;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer")
     * @Assert\Regex("/^[1-5]/")
     */
    private $score;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="ProductSupplier", mappedBy="supplier")
     */
    private $productSupplier;

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
     * Set name
     *
     * @param string $name
     *
     * @return Supplier
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Supplier
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set mail
     *
     * @param string $mail
     *
     * @return Supplier
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Get mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     *
     * @return Supplier
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set town
     *
     * @param string $town
     *
     * @return Supplier
     */
    public function setTown($town)
    {
        $this->town = $town;

        return $this;
    }

    /**
     * Get town
     *
     * @return string
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * Set siret
     *
     * @param string $siret
     *
     * @return Supplier
     */
    public function setSiret($siret)
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * Get siret
     *
     * @return string
     */
    public function getSiret()
    {
        return $this->siret;
    }

    /**
     * Set web
     *
     * @param string $web
     *
     * @return Supplier
     */
    public function setWeb($web)
    {
        $this->web = $web;

        return $this;
    }

    /**
     * Get web
     *
     * @return string
     */
    public function getWeb()
    {
        return $this->web;
    }

    /**
     * Set deliveryTime
     *
     * @param integer $deliveryTime
     *
     * @return Supplier
     */
    public function setDeliveryTime($deliveryTime)
    {
        $this->deliveryTime = $deliveryTime;

        return $this;
    }

    /**
     * Get deliveryTime
     *
     * @return int
     */
    public function getDeliveryTime()
    {
        return $this->deliveryTime;
    }

    /**
     * Set score
     *
     * @param integer $score
     *
     * @return Supplier
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productSupplier = new ArrayCollection();
    }

    /**
     * Add productSupplier
     *
     * @param ProductSupplier $productSupplier
     *
     * @return Supplier
     */
    public function addProductSupplier(ProductSupplier $productSupplier)
    {
        $this->productSupplier[] = $productSupplier;

        return $this;
    }

    /**
     * Remove productSupplier
     *
     * @param ProductSupplier $productSupplier
     */
    public function removeProductSupplier(ProductSupplier $productSupplier)
    {
        $this->productSupplier->removeElement($productSupplier);
    }

    /**
     * Get productSupplier
     *
     * @return Collection
     */
    public function getProductSupplier()
    {
        return $this->productSupplier;
    }

    /**
     * @return bool
     * @Assert\IsTrue(message="L'adresse doit être différente du nom et de l'email")
     */
    public function isAddressLegal()
    {
        return ($this->address != $this->mail && $this->address != $this->name);
    }
}
