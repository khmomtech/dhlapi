<?php

namespace Wk\DhlApiBundle\Model\B2b\Response;

use Wk\DhlApiBundle\Model\B2b\Version;
use Wk\DhlApiBundle\Model\B2b\StatusInformation;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class CancelPickupResponse
 *
 * @package Wk\DhlApiBundle\Model\B2b\Response
 * @XmlRoot("response")
 */
class CancelPickupResponse extends GetVersionResponse
{
    /**
     *
     * @var StatusInformation $Status
     * @access public
     * @Type("Wk\DhlApiBundle\Model\B2b\StatusInformation")
     * @SerializedName("status")
     */
    public $Status = null;

    /**
     *
     * @param Version           $version
     * @param StatusInformation $status
     *
     * @access public
     */
    public function __construct(Version $version, StatusInformation $status)
    {
        parent::__construct($version);

        $this->Status = $status;
    }

}
