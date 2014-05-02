<?php

namespace Wk\DhlApiBundle\Model\B2b\Request;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\ReadOnly;

use Wk\DhlApiBundle\Model\B2b\Version;
use Wk\DhlApiBundle\Model\B2b\PickupBookingInformationType;
use Wk\DhlApiBundle\Model\B2b\PickupAddressType;
use Wk\DhlApiBundle\Model\B2b\PickupOrdererType;

/**
 * Class BookPickupRequest
 * @package Wk\DhlApiBundle\Model\B2b\Request
 * @XmlRoot("pickup")
 */
class BookPickupRequest
{

    /**
     * @Type("Wk\DhlApiBundle\Model\B2b\Version")
     * @SerializedName("version")
     * @ReadOnly
     */
    public $Version = null;

    /**
     *
     * @Type("Wk\DhlApiBundle\Model\B2b\PickupBookingInformationType")
     * @SerializedName("information")
     */
    public $BookingInformation = null;

    /**
     *
     * @Type("Wk\DhlApiBundle\Model\B2b\PickupAddressType")
     * @SerializedName("address")
     */
    public $PickupAddress = null;

    /**
     *
     * @Type("Wk\DhlApiBundle\Model\B2b\PickupOrdererType")
     * @SerializedName("orderer")
     */
    public $ContactOrderer = null;

    /**
     *
     * @param Version $Version
     * @param PickupBookingInformationType $BookingInformation
     * @param PickupAddressType $PickupAddress
     * @param PickupOrdererType $ContactOrderer
     * @access public
     */
    public function __construct(Version $Version, PickupBookingInformationType $BookingInformation, PickupAddressType $PickupAddress, PickupOrdererType $ContactOrderer)
    {
        $this->Version = $Version;
        $this->BookingInformation = $BookingInformation;
        $this->PickupAddress = $PickupAddress;
        $this->ContactOrderer = $ContactOrderer;
    }

}
