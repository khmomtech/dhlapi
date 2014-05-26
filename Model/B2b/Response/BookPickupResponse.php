<?php

namespace Wk\DhlApiBundle\Model\B2b\Response;

use Wk\DhlApiBundle\Model\B2b\Version;
use Wk\DhlApiBundle\Model\B2b\StatusInformation;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\XmlRoot;

/**
 * Class BookPickupResponse
 *
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("response")
 */
class BookPickupResponse extends CancelPickupResponse
{
    /**
     * @var string $ConfirmationNumber
     * @access public
     * @Type("string")
     * @SerializedName("confirmation_number")
     */
    public $ConfirmationNumber = null;

    /**
     * @var string $ShipmentNumber
     * @access public
     * @Type("string")
     * @SerializedName("shipment_number")
     */
    public $ShipmentNumber = null;

    /**
     * Class constructor
     *
     * @param Version           $version
     * @param StatusInformation $status
     * @param string            $confirmationNumber
     * @param string            $shipmentNumber
     *
     * @access public
     */
    public function __construct(Version $version, StatusInformation $status, $confirmationNumber = null, $shipmentNumber = null)
    {
        parent::__construct($version, $status);

        $this->ConfirmationNumber = $confirmationNumber;
        $this->ShipmentNumber = $shipmentNumber;
    }
}
