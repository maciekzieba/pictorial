<?php

namespace Mz\PictorialBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VisitCost
 *
 * @ORM\Table(name="visit_cost", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_visit_cost_visit_role1_idx", columns={"visit_role"}), @ORM\Index(name="fk_visit_cost_user1_idx", columns={"user"}), @ORM\Index(name="fk_visit_cost_visit1_idx", columns={"visit"}), @ORM\Index(name="fk_visit_cost_user2_idx", columns={"created_by"}), @ORM\Index(name="fk_visit_cost_user3_idx", columns={"updated_by"})})
 * @ORM\Entity
 */
class VisitCost
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
     * @var float
     *
     * @ORM\Column(name="price", type="float", precision=10, scale=0, nullable=false)
     */
    private $price = '0';

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $user;

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
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="updated_by", referencedColumnName="id")
     * })
     */
    private $updatedBy;

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
     * @var \VisitRole
     *
     * @ORM\ManyToOne(targetEntity="VisitRole")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="visit_role", referencedColumnName="id")
     * })
     */
    private $visitRole;



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
     * @return VisitCost
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
     * @return VisitCost
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
     * Set price
     *
     * @param float $price
     *
     * @return VisitCost
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set user
     *
     * @param \Mz\PictorialBundle\Entity\User $user
     *
     * @return VisitCost
     */
    public function setUser(\Mz\PictorialBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Mz\PictorialBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set createdBy
     *
     * @param \Mz\PictorialBundle\Entity\User $createdBy
     *
     * @return VisitCost
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
     * Set updatedBy
     *
     * @param \Mz\PictorialBundle\Entity\User $updatedBy
     *
     * @return VisitCost
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
     * Set visit
     *
     * @param \Mz\PictorialBundle\Entity\Visit $visit
     *
     * @return VisitCost
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

    /**
     * Set visitRole
     *
     * @param \Mz\PictorialBundle\Entity\VisitRole $visitRole
     *
     * @return VisitCost
     */
    public function setVisitRole(\Mz\PictorialBundle\Entity\VisitRole $visitRole = null)
    {
        $this->visitRole = $visitRole;

        return $this;
    }

    /**
     * Get visitRole
     *
     * @return \Mz\PictorialBundle\Entity\VisitRole
     */
    public function getVisitRole()
    {
        return $this->visitRole;
    }
}
