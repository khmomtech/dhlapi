<?php

namespace Wk\DhlApiBundle\Model\B2b\Request;

use Wk\DhlApiBundle\Model\B2b\Version;
use Wk\DhlApiBundle\Model\B2b\ShipmentNumberType;
use Wk\DhlApiBundle\Model\B2b\ShipmentOrderDDType;

class UpdateShipmentDDRequest
{

  /**
   * 
   * @var Version $Version
   * @access public
   */
  public $Version = null;

  /**
   * 
   * @var ShipmentNumberType $ShipmentNumber
   * @access public
   */
  public $ShipmentNumber = null;

  /**
   * 
   * @var ShipmentOrderDDType $ShipmentOrder
   * @access public
   */
  public $ShipmentOrder = null;

  /**
   * 
   * @param Version $Version
   * @param ShipmentNumberType $ShipmentNumber
   * @param ShipmentOrderDDType $ShipmentOrder
   * @access public
   */
  public function __construct(Version $Version, ShipmentNumberType $ShipmentNumber, ShipmentOrderDDType $ShipmentOrder)
  {
    $this->Version = $Version;
    $this->ShipmentNumber = $ShipmentNumber;
    $this->ShipmentOrder = $ShipmentOrder;
  }

}
