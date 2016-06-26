<?php

namespace Mz\PictorialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Package
 *
 * @ORM\Table(name="package", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_package_user1_idx", columns={"updated_by"}), @ORM\Index(name="fk_package_user2_idx", columns={"created_by"})})
 * @ORM\Entity
 */
class Package
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=128, nullable=false)
     */
    private $number;

    /**
     * @var integer
     *
     * @ORM\Column(name="month", type="integer", nullable=false)
     */
    private $month;

    /**
     * @var integer
     *
     * @ORM\Column(name="year", type="integer", nullable=false)
     */
    private $year;

    /**
     * @var integer
     *
     * @ORM\Column(name="visits_quantity", type="integer", nullable=false)
     */
    private $visitsQuantity;

    /**
     * @var float
     *
     * @ORM\Column(name="price_net", type="float", precision=10, scale=0, nullable=false)
     */
    private $priceNet;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=64, nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice_number", type="string", length=128, nullable=true)
     */
    private $invoiceNumber;

    /**
     * @var float
     *
     * @ORM\Column(name="invoice_value_net", type="float", precision=10, scale=0, nullable=true)
     */
    private $invoiceValueNet;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="updated_by", referencedColumnName="id")
     * })
     */
    private $updatedBy;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     * })
     */
    private $createdBy;



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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Package
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Package
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set number
     *
     * @param string $number
     *
     * @return Package
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set month
     *
     * @param integer $month
     *
     * @return Package
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return integer
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return Package
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set visitsQuantity
     *
     * @param integer $visitsQuantity
     *
     * @return Package
     */
    public function setVisitsQuantity($visitsQuantity)
    {
        $this->visitsQuantity = $visitsQuantity;

        return $this;
    }

    /**
     * Get visitsQuantity
     *
     * @return integer
     */
    public function getVisitsQuantity()
    {
        return $this->visitsQuantity;
    }

    /**
     * Set priceNet
     *
     * @param float $priceNet
     *
     * @return Package
     */
    public function setPriceNet($priceNet)
    {
        $this->priceNet = $priceNet;

        return $this;
    }

    /**
     * Get priceNet
     *
     * @return float
     */
    public function getPriceNet()
    {
        return $this->priceNet;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Package
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Package
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set invoiceNumber
     *
     * @param string $invoiceNumber
     *
     * @return Package
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    /**
     * Get invoiceNumber
     *
     * @return string
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * Set invoiceValueNet
     *
     * @param float $invoiceValueNet
     *
     * @return Package
     */
    public function setInvoiceValueNet($invoiceValueNet)
    {
        $this->invoiceValueNet = $invoiceValueNet;

        return $this;
    }

    /**
     * Get invoiceValueNet
     *
     * @return float
     */
    public function getInvoiceValueNet()
    {
        return $this->invoiceValueNet;
    }

    /**
     * Set updatedBy
     *
     * @param \Mz\PictorialBundle\Entity\User $updatedBy
     *
     * @return Package
     */
    public function setUpdatedBy(\Mz\PictorialBundle\Entity\User $updatedBy = null)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return \Mz\PictorialBundle\Entity\User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set createdBy
     *
     * @param \Mz\PictorialBundle\Entity\User $createdBy
     *
     * @return Package
     */
    public function setCreatedBy(\Mz\PictorialBundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \Mz\PictorialBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}
