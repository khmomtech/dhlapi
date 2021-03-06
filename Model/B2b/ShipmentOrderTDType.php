<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class ShipmentOrderTDType
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("order");
 */
class ShipmentOrderTDType
{
    /**
     *
     * @var int $SequenceNumber
     * @access public
     * @Type("integer")
     * @XmlAttribute
     * @SerializedName("sequence")
     */
    public $SequenceNumber = null;

    /**
     *
     * @var ShipmentTDType $Shipment
     * @access public
     * @Type("Wk\DhlApiBundle\Model\B2b\ShipmentDDType")
     * @SerializedName("shipment")
     */
    public $Shipment = null;

    /**
     *
     * @var Pickup $Pickup
     * @access public
     * @Type("Wk\DhlApiBundle\Model\B2b\Pickup")
     * @SerializedName("pickup")
     */
    public $Pickup = null;

    /**
     *
     * @var string $LabelResponseType
     * @access public
     * @Type("string")
     * @SerializedName("label_type")
     * @XmlAttribute
     */
    public $LabelResponseType = 'XML';

    /**
     *
     * @param int $SequenceNumber
     * @param ShipmentTDType $Shipment
     * @param Pickup $Pickup
     * @param string $LabelResponseType
     * @access public
     */
    public function __construct($SequenceNumber, ShipmentTDType $Shipment, Pickup $Pickup = null, $LabelResponseType = 'XML')
    {
        $this->SequenceNumber = $SequenceNumber;
        $this->Shipment = $Shipment;
        $this->Pickup = $Pickup;
        $this->LabelResponseType = $LabelResponseType;
    }

}
