<?php

namespace Wk\DhlApiBundle\Model\B2b\Request;

use Wk\DhlApiBundle\Model\B2b\Version;
use Wk\DhlApiBundle\Model\B2b\ShipmentOrderTDType;

class CreateShipmentTDRequest
{

  /**
   * 
   * @var Version $Version
   * @access public
   */
  public $Version = null;

  /**
   * 
   * @var ShipmentOrderTDType $ShipmentOrder
   * @access public
   */
  public $ShipmentOrder = null;

  /**
   * 
   * @param Version $Version
   * @param ShipmentOrderTDType $ShipmentOrder
   * @access public
   */
  public function __construct(Version $Version, ShipmentOrderTDType $ShipmentOrder)
  {
    $this->Version = $Version;
    $this->ShipmentOrder = $ShipmentOrder;
  }

}
