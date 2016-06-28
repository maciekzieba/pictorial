<?php

namespace Mz\PictorialBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Mz\PictorialBundle\Validator\Constraints\PackageVisits;

/**
 * Package
 *
 * @ORM\Table(name="package", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_package_user1_idx", columns={"updated_by"}), @ORM\Index(name="fk_package_user2_idx", columns={"created_by"})})
 * @ORM\Entity
 * @PackageVisits()
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
     * @var \DateTime
     *
     * @ORM\Column(name="validity_date", type="datetime", nullable=false)
     */
    private $validityDate;

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
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Visit", mappedBy="package", cascade="persist", orphanRemoval=true)
     */
    private $visits;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->visits = new ArrayCollection();
    }

    /**
     * Get visits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVisits()
    {
        return $this->visits;
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

    /**
     * @return \DateTime
     */
    public function getValidityDate()
    {
        return $this->validityDate;
    }

    /**
     * @param $validityDate
     * @return $this
     */
    public function setValidityDate($validityDate)
    {
        $this->validityDate = $validityDate;
        return $this;
    }

    /**
     * @return float
     */
    public function getPriceNetPerVisit()
    {
        if ($this->visitsQuantity > 0) {
            return ($this->priceNet / $this->visitsQuantity);
        }
        return 0.00;
    }

    /**
     * @return int
     */
    public function getVisitsLeft()
    {
        return $this->visitsQuantity - count($this->visits);
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->id." ".$this->validityDate->format("m/Y")." (Pozostało:".($this->getVisitsLeft()).")";
    }
}
