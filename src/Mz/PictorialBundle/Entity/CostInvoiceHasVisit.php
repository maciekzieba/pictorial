<?php

namespace Mz\PictorialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CostInvoiceHasVisit
 *
 * @ORM\Table(name="cost_invoice_has_visit", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_cost_invoice_has_visit_visit1_idx", columns={"visit"}), @ORM\Index(name="fk_cost_invoice_has_visit_cost_invoice1_idx", columns={"cost_invoice"})})
 * @ORM\Entity
 */
class CostInvoiceHasVisit
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
     * @var \CostInvoice
     *
     * @ORM\ManyToOne(targetEntity="CostInvoice")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cost_invoice", referencedColumnName="id")
     * })
     */
    private $costInvoice;

    /**
     * @var \Visit
     *
     * @ORM\ManyToOne(targetEntity="Visit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="visit", referencedColumnName="id")
     * })
     */
    private $visit;



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
     * Set costInvoice
     *
     * @param \Mz\PictorialBundle\Entity\CostInvoice $costInvoice
     *
     * @return CostInvoiceHasVisit
     */
    public function setCostInvoice(\Mz\PictorialBundle\Entity\CostInvoice $costInvoice = null)
    {
        $this->costInvoice = $costInvoice;

        return $this;
    }

    /**
     * Get costInvoice
     *
     * @return \Mz\PictorialBundle\Entity\CostInvoice
     */
    public function getCostInvoice()
    {
        return $this->costInvoice;
    }

    /**
     * Set visit
     *
     * @param \Mz\PictorialBundle\Entity\Visit $visit
     *
     * @return CostInvoiceHasVisit
     */
    public function setVisit(\Mz\PictorialBundle\Entity\Visit $visit = null)
    {
        $this->visit = $visit;

        return $this;
    }

    /**
     * Get visit
     *
     * @return \Mz\PictorialBundle\Entity\Visit
     */
    public function getVisit()
    {
        return $this->visit;
    }
}
