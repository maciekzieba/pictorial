<?php

namespace Mz\PictorialBundle\Model;

use Mz\PictorialBundle\Entity\Package;

class ReportClientFilter
{
    /** @var  Package */
    protected $package;

    /** @var  \DateTime */
    protected $packageDateFrom;

    /** @var  \DateTime */
    protected $packageDateTo;

    /**
     * @return Package
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @param Package $package
     */
    public function setPackage(Package $package)
    {
        $this->package = $package;
    }

    /**
     * @return \DateTime
     */
    public function getPackageDateFrom()
    {
        return $this->packageDateFrom;
    }

    /**
     * @param \DateTime $packageDateFrom
     */
    public function setPackageDateFrom(\DateTime $packageDateFrom)
    {
        $this->packageDateFrom = $packageDateFrom;
    }

    /**
     * @return \DateTime
     */
    public function getPackageDateTo()
    {
        return $this->packageDateTo;
    }

    /**
     * @param \DateTime $packageDateTo
     */
    public function setPackageDateTo(\DateTime $packageDateTo)
    {
        $this->packageDateTo = $packageDateTo;
    }


}
