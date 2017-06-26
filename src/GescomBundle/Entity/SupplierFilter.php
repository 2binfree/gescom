<?php

namespace GescomBundle\Entity;

/**
 * Supplier
 */
class SupplierFilter
{
    /**
     * @var string
     *
     */
    public $name;

    /**
     * @var string
     */
    public $postalCode;

    /**
     * @var string
     */
    public $town;

    /**
     * @var int
     */
    public $deliveryTime;

    /**
     * @var int
     */
    public $score;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return SupplierFilter
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
     * Set postalCode
     *
     * @param string $postalCode
     *
     * @return SupplierFilter
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
     * @return SupplierFilter
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
     * Set deliveryTime
     *
     * @param integer $deliveryTime
     *
     * @return SupplierFilter
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
     * @return SupplierFilter
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
}
