<?php

namespace Wk\DhlApiBundle\Model\B2b\Response;

use Wk\DhlApiBundle\Model\B2b\Response\CancelPickupResponse;
use Wk\DhlApiBundle\Model\B2b\Version;
use Wk\DhlApiBundle\Model\B2b\StatusInformation;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\XmlRoot;

/**
 * Class BookPickupResponse
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("response")
 */
class BookPickupResponse extends CancelPickupResponse
{
    /**
     *
     * @var string $ConfirmationNumber
     * @access public
     * @Type("string")
     * @SerializedName("confirmation_number")
     */
    public $ConfirmationNumber = null;

    /**
     *
     * @var string $ShipmentNumber
     * @access public
     * @Type("string")
     * @SerializedName("shipment_number")
     */
    public $ShipmentNumber = null;

    /**
     *
     * @param Version $Version
     * @param StatusInformation $Status
     * @param string $ConfirmationNumber
     * @param string $ShipmentNumber
     * @access public
     */
    public function __construct(Version $Version, StatusInformation $Status, $ConfirmationNumber, $ShipmentNumber)
    {
        parent::__construct($Version, $Status);

        $this->ConfirmationNumber = $ConfirmationNumber;
        $this->ShipmentNumber = $ShipmentNumber;
    }
}
