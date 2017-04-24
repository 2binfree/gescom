<?php

namespace GescomBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * ProductSupplier
 *
 * @ORM\Table(name="product_supplier")
 * @ORM\Entity(repositoryClass="GescomBundle\Repository\ProductSupplierRepository")
 */
class ProductSupplier
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
     * @var Supplier
     *
     * @ORM\ManyToOne(targetEntity="Supplier", inversedBy="productSupplier")
     */
    private $supplier;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="productSupplier")
     */
    private $product;

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
     * Set supplier
     *
     * @param Supplier $supplier
     *
     * @return ProductSupplier
     */
    public function setSupplier(Supplier $supplier = null)
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * Get supplier
     *
     * @return SSupplier
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * Set product
     *
     * @param Product $product
     *
     * @return ProductSupplier
     */
    public function setProduct(Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }
}
