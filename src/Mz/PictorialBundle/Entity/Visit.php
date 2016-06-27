<?php

namespace Mz\PictorialBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Visit
 *
 * @ORM\Table(name="visit", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_package_user1_idx", columns={"updated_by"}), @ORM\Index(name="fk_package_user2_idx", columns={"created_by"}), @ORM\Index(name="fk_visit_package1_idx", columns={"package"}), @ORM\Index(name="fk_visit_user1_idx", columns={"scounting_owner"}), @ORM\Index(name="fk_visit_user2_idx", columns={"photo_owner"}), @ORM\Index(name="fk_visit_user3_idx", columns={"interview_owner"}), @ORM\Index(name="fk_visit_user4_idx", columns={"postproduction_owner"}), @ORM\Index(name="fk_visit_user5_idx", columns={"editing_owner"}), @ORM\Index(name="fk_visit_user6_idx", columns={"provision_owner"})})
 * @ORM\Entity
 */
class Visit
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
     * @ORM\Column(name="number", type="string", length=45, nullable=false)
     */
    private $number;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="visit_date", type="datetime", nullable=false)
     */
    private $visitDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="package_sorting", type="integer", nullable=false)
     */
    private $packageSorting = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=128, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=128, nullable=true)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=128, nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="district", type="string", length=255, nullable=false)
     */
    private $district;

    /**
     * @var string
     *
     * @ORM\Column(name="card_number", type="string", length=64, nullable=true)
     */
    private $cardNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="lp_id", type="string", length=64, nullable=true)
     */
    private $lpId;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_source", type="string", length=45, nullable=true)
     */
    private $contactSource;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=16777215, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="realization_status", type="string", length=45, nullable=false)
     */
    private $realizationStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_status", type="string", length=45, nullable=false)
     */
    private $paymentStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="restrictions", type="text", length=16777215, nullable=true)
     */
    private $restrictions;

    /**
     * @var boolean
     *
     * @ORM\Column(name="newsletter", type="boolean", nullable=true)
     */
    private $newsletter;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="rights_deadline", type="datetime", nullable=true)
     */
    private $rightsDeadline;

    /**
     * @var float
     *
     * @ORM\Column(name="scounting_share", type="float", precision=10, scale=0, nullable=true)
     */
    private $scountingShare = 5.00;

    /**
     * @var float
     *
     * @ORM\Column(name="photo_share", type="float", precision=10, scale=0, nullable=true)
     */
    private $photoShare = 50.00;

    /**
     * @var float
     *
     * @ORM\Column(name="interview_share", type="float", precision=10, scale=0, nullable=true)
     */
    private $interviewShare = 20.00;

    /**
     * @var float
     *
     * @ORM\Column(name="postproduction_share", type="float", precision=10, scale=0, nullable=true)
     */
    private $postproductionShare = 15.00;

    /**
     * @var float
     *
     * @ORM\Column(name="editing_share", type="float", precision=10, scale=0, nullable=true)
     */
    private $editingShare = 5.00;

    /**
     * @var float
     *
     * @ORM\Column(name="provision_share", type="float", precision=10, scale=0, nullable=true)
     */
    private $provisionShare = 5.00;

    /**
     * @var float
     *
     * @ORM\Column(name="externals_costs", type="float", precision=10, scale=0, nullable=true)
     */
    private $externalsCosts;

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
     * @var \Package
     *
     * @ORM\ManyToOne(targetEntity="Package")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="package", referencedColumnName="id")
     * })
     */
    private $package;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="scounting_owner", referencedColumnName="id")
     * })
     */
    private $scountingOwner;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="photo_owner", referencedColumnName="id")
     * })
     */
    private $photoOwner;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="interview_owner", referencedColumnName="id")
     * })
     */
    private $interviewOwner;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="postproduction_owner", referencedColumnName="id")
     * })
     */
    private $postproductionOwner;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="editing_owner", referencedColumnName="id")
     * })
     */
    private $editingOwner;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="provision_owner", referencedColumnName="id")
     * })
     */
    private $provisionOwner;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Publication", mappedBy="visit", cascade="persist", orphanRemoval=true)
     */
    private $publications;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->visits = new ArrayCollection();
    }

    /**
     * Get publications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPublications()
    {
        return $this->publications;
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
     * @return Visit
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
     * @return Visit
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
     * @return Visit
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
     * Set visitDate
     *
     * @param \DateTime $visitDate
     *
     * @return Visit
     */
    public function setVisitDate($visitDate)
    {
        $this->visitDate = $visitDate;

        return $this;
    }

    /**
     * Get visitDate
     *
     * @return \DateTime
     */
    public function getVisitDate()
    {
        return $this->visitDate;
    }

    /**
     * Set packageSorting
     *
     * @param integer $packageSorting
     *
     * @return Visit
     */
    public function setPackageSorting($packageSorting)
    {
        $this->packageSorting = $packageSorting;

        return $this;
    }

    /**
     * Get packageSorting
     *
     * @return integer
     */
    public function getPackageSorting()
    {
        return $this->packageSorting;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Visit
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Visit
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Visit
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set district
     *
     * @param string $district
     *
     * @return Visit
     */
    public function setDistrict($district)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Get district
     *
     * @return string
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Set cardNumber
     *
     * @param string $cardNumber
     *
     * @return Visit
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;

        return $this;
    }

    /**
     * Get cardNumber
     *
     * @return string
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * Set lpId
     *
     * @param string $lpId
     *
     * @return Visit
     */
    public function setLpId($lpId)
    {
        $this->lpId = $lpId;

        return $this;
    }

    /**
     * Get lpId
     *
     * @return string
     */
    public function getLpId()
    {
        return $this->lpId;
    }

    /**
     * Set contactSource
     *
     * @param string $contactSource
     *
     * @return Visit
     */
    public function setContactSource($contactSource)
    {
        $this->contactSource = $contactSource;

        return $this;
    }

    /**
     * Get contactSource
     *
     * @return string
     */
    public function getContactSource()
    {
        return $this->contactSource;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Visit
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
     * Set realizationStatus
     *
     * @param string $realizationStatus
     *
     * @return Visit
     */
    public function setRealizationStatus($realizationStatus)
    {
        $this->realizationStatus = $realizationStatus;

        return $this;
    }

    /**
     * Get realizationStatus
     *
     * @return string
     */
    public function getRealizationStatus()
    {
        return $this->realizationStatus;
    }

    /**
     * Set paymentStatus
     *
     * @param string $paymentStatus
     *
     * @return Visit
     */
    public function setPaymentStatus($paymentStatus)
    {
        $this->paymentStatus = $paymentStatus;

        return $this;
    }

    /**
     * Get paymentStatus
     *
     * @return string
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * Set restrictions
     *
     * @param string $restrictions
     *
     * @return Visit
     */
    public function setRestrictions($restrictions)
    {
        $this->restrictions = $restrictions;

        return $this;
    }

    /**
     * Get restrictions
     *
     * @return string
     */
    public function getRestrictions()
    {
        return $this->restrictions;
    }

    /**
     * Set newsletter
     *
     * @param boolean $newsletter
     *
     * @return Visit
     */
    public function setNewsletter($newsletter)
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    /**
     * Get newsletter
     *
     * @return boolean
     */
    public function getNewsletter()
    {
        return $this->newsletter;
    }

    /**
     * Set rightsDeadline
     *
     * @param \DateTime $rightsDeadline
     *
     * @return Visit
     */
    public function setRightsDeadline($rightsDeadline)
    {
        $this->rightsDeadline = $rightsDeadline;

        return $this;
    }

    /**
     * Get rightsDeadline
     *
     * @return \DateTime
     */
    public function getRightsDeadline()
    {
        return $this->rightsDeadline;
    }

    /**
     * Set scountingShare
     *
     * @param float $scountingShare
     *
     * @return Visit
     */
    public function setScountingShare($scountingShare)
    {
        $this->scountingShare = $scountingShare;

        return $this;
    }

    /**
     * Get scountingShare
     *
     * @return float
     */
    public function getScountingShare()
    {
        return $this->scountingShare;
    }

    /**
     * Set photoShare
     *
     * @param float $photoShare
     *
     * @return Visit
     */
    public function setPhotoShare($photoShare)
    {
        $this->photoShare = $photoShare;

        return $this;
    }

    /**
     * Get photoShare
     *
     * @return float
     */
    public function getPhotoShare()
    {
        return $this->photoShare;
    }

    /**
     * Set interviewShare
     *
     * @param float $interviewShare
     *
     * @return Visit
     */
    public function setInterviewShare($interviewShare)
    {
        $this->interviewShare = $interviewShare;

        return $this;
    }

    /**
     * Get interviewShare
     *
     * @return float
     */
    public function getInterviewShare()
    {
        return $this->interviewShare;
    }

    /**
     * Set postproductionShare
     *
     * @param float $postproductionShare
     *
     * @return Visit
     */
    public function setPostproductionShare($postproductionShare)
    {
        $this->postproductionShare = $postproductionShare;

        return $this;
    }

    /**
     * Get postproductionShare
     *
     * @return float
     */
    public function getPostproductionShare()
    {
        return $this->postproductionShare;
    }

    /**
     * Set editingShare
     *
     * @param float $editingShare
     *
     * @return Visit
     */
    public function setEditingShare($editingShare)
    {
        $this->editingShare = $editingShare;

        return $this;
    }

    /**
     * Get editingShare
     *
     * @return float
     */
    public function getEditingShare()
    {
        return $this->editingShare;
    }

    /**
     * Set provisionShare
     *
     * @param float $provisionShare
     *
     * @return Visit
     */
    public function setProvisionShare($provisionShare)
    {
        $this->provisionShare = $provisionShare;

        return $this;
    }

    /**
     * Get provisionShare
     *
     * @return float
     */
    public function getProvisionShare()
    {
        return $this->provisionShare;
    }

    /**
     * Set externalsCosts
     *
     * @param float $externalsCosts
     *
     * @return Visit
     */
    public function setExternalsCosts($externalsCosts)
    {
        $this->externalsCosts = $externalsCosts;

        return $this;
    }

    /**
     * Get externalsCosts
     *
     * @return float
     */
    public function getExternalsCosts()
    {
        return $this->externalsCosts;
    }

    /**
     * Set updatedBy
     *
     * @param \Mz\PictorialBundle\Entity\User $updatedBy
     *
     * @return Visit
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
     * @return Visit
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
     * Set package
     *
     * @param \Mz\PictorialBundle\Entity\Package $package
     *
     * @return Visit
     */
    public function setPackage(\Mz\PictorialBundle\Entity\Package $package = null)
    {
        $this->package = $package;

        return $this;
    }

    /**
     * Get package
     *
     * @return \Mz\PictorialBundle\Entity\Package
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * Set scountingOwner
     *
     * @param \Mz\PictorialBundle\Entity\User $scountingOwner
     *
     * @return Visit
     */
    public function setScountingOwner(\Mz\PictorialBundle\Entity\User $scountingOwner = null)
    {
        $this->scountingOwner = $scountingOwner;

        return $this;
    }

    /**
     * Get scountingOwner
     *
     * @return \Mz\PictorialBundle\Entity\User
     */
    public function getScountingOwner()
    {
        return $this->scountingOwner;
    }

    /**
     * Set photoOwner
     *
     * @param \Mz\PictorialBundle\Entity\User $photoOwner
     *
     * @return Visit
     */
    public function setPhotoOwner(\Mz\PictorialBundle\Entity\User $photoOwner = null)
    {
        $this->photoOwner = $photoOwner;

        return $this;
    }

    /**
     * Get photoOwner
     *
     * @return \Mz\PictorialBundle\Entity\User
     */
    public function getPhotoOwner()
    {
        return $this->photoOwner;
    }

    /**
     * Set interviewOwner
     *
     * @param \Mz\PictorialBundle\Entity\User $interviewOwner
     *
     * @return Visit
     */
    public function setInterviewOwner(\Mz\PictorialBundle\Entity\User $interviewOwner = null)
    {
        $this->interviewOwner = $interviewOwner;

        return $this;
    }

    /**
     * Get interviewOwner
     *
     * @return \Mz\PictorialBundle\Entity\User
     */
    public function getInterviewOwner()
    {
        return $this->interviewOwner;
    }

    /**
     * Set postproductionOwner
     *
     * @param \Mz\PictorialBundle\Entity\User $postproductionOwner
     *
     * @return Visit
     */
    public function setPostproductionOwner(\Mz\PictorialBundle\Entity\User $postproductionOwner = null)
    {
        $this->postproductionOwner = $postproductionOwner;

        return $this;
    }

    /**
     * Get postproductionOwner
     *
     * @return \Mz\PictorialBundle\Entity\User
     */
    public function getPostproductionOwner()
    {
        return $this->postproductionOwner;
    }

    /**
     * Set editingOwner
     *
     * @param \Mz\PictorialBundle\Entity\User $editingOwner
     *
     * @return Visit
     */
    public function setEditingOwner(\Mz\PictorialBundle\Entity\User $editingOwner = null)
    {
        $this->editingOwner = $editingOwner;

        return $this;
    }

    /**
     * Get editingOwner
     *
     * @return \Mz\PictorialBundle\Entity\User
     */
    public function getEditingOwner()
    {
        return $this->editingOwner;
    }

    /**
     * Set provisionOwner
     *
     * @param \Mz\PictorialBundle\Entity\User $provisionOwner
     *
     * @return Visit
     */
    public function setProvisionOwner(\Mz\PictorialBundle\Entity\User $provisionOwner = null)
    {
        $this->provisionOwner = $provisionOwner;

        return $this;
    }

    /**
     * Get provisionOwner
     *
     * @return \Mz\PictorialBundle\Entity\User
     */
    public function getProvisionOwner()
    {
        return $this->provisionOwner;
    }

    /**
     * @return float
     */
    public function getPriceNetScouting()
    {
        if ($this->package instanceof Package) {
            return ($this->package->getPriceNetPerVisit()*$this->getScountingShare())/100;
        }
        return 0.00;
    }

    /**
     * @return float
     */
    public function getPriceNetPhoto()
    {
        if ($this->package instanceof Package) {
            return ($this->package->getPriceNetPerVisit()*$this->getPhotoShare())/100;
        }
        return 0.00;
    }

    /**
     * @return float
     */
    public function getPriceNetEditing()
    {
        if ($this->package instanceof Package) {
            return ($this->package->getPriceNetPerVisit()*$this->getEditingShare())/100;
        }
        return 0.00;
    }

    /**
     * @return float
     */
    public function getPriceNetInterview()
    {
        if ($this->package instanceof Package) {
            return ($this->package->getPriceNetPerVisit()*$this->getInterviewShare())/100;
        }
        return 0.00;
    }

    /**
     * @return float
     */
    public function getPriceNetPostproduction()
    {
        if ($this->package instanceof Package) {
            return ($this->package->getPriceNetPerVisit()*$this->getPostproductionShare())/100;
        }
        return 0.00;
    }

    /**
     * @return float
     */
    public function getPriceNetProvision()
    {
        if ($this->package instanceof Package) {
            return ($this->package->getPriceNetPerVisit()*$this->getProvisionShare())/100;
        }
        return 0.00;
    }

}
