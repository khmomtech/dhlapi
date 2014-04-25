<?php

namespace Wk\DhlApiBundle\Lib\B2b;

class ShipmentOrderTDType
{

  /**
   * 
   * @var SequenceNumber $SequenceNumber
   * @access public
   */
  public $SequenceNumber = null;

  /**
   * 
   * @var Shipment $Shipment
   * @access public
   */
  public $Shipment = null;

  /**
   * 
   * @var Pickup $Pickup
   * @access public
   */
  public $Pickup = null;

  /**
   * 
   * @var LabelResponseType $LabelResponseType
   * @access public
   */
  public $LabelResponseType = null;

  /**
   * 
   * @param int $SequenceNumber
   * @param Shipment $Shipment
   * @param Pickup $Pickup
   * @param string $LabelResponseType
   * @access public
   */
  public function __construct($SequenceNumber, $Shipment, $Pickup, $LabelResponseType)
  {
    $this->SequenceNumber = $SequenceNumber;
    $this->Shipment = $Shipment;
    $this->Pickup = $Pickup;
    $this->LabelResponseType = $LabelResponseType;
  }

}
