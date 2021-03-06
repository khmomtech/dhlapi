<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class ShipmentOrderDDType
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("order");
 */
class ShipmentOrderDDType
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
   * @var ShipmentDDType $ShipmentDDType
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
   * @var bool $PRINTONLYIFCODEABLE
   * @access public
   * @Type("boolean")
   * @SerializedName("only_if_codeable")
   * @XmlAttribute
   */
  public $PRINTONLYIFCODEABLE = false;

  /**
   * 
   * @param int $SequenceNumber
   * @param ShipmentDDType $Shipment
   * @param Pickup $Pickup
   * @param string $LabelResponseType
   * @param bool $PRINTONLYIFCODEABLE
   * @access public
   */
  public function __construct($SequenceNumber, ShipmentDDType $Shipment, Pickup $Pickup = null, $LabelResponseType = 'XML', $PRINTONLYIFCODEABLE = false)
  {
    $this->SequenceNumber = $SequenceNumber;
    $this->Shipment = $Shipment;
    $this->Pickup = $Pickup;
    $this->LabelResponseType = $LabelResponseType;
    $this->PRINTONLYIFCODEABLE = $PRINTONLYIFCODEABLE;
  }

}
