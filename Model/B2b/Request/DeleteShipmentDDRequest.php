<?php

namespace Wk\DhlApiBundle\Model\B2b\Request;

use Wk\DhlApiBundle\Model\B2b\Version;
use Wk\DhlApiBundle\Model\B2b\ShipmentNumberType;

class DeleteShipmentDDRequest
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
   * @param Version $Version
   * @param ShipmentNumberType $ShipmentNumber
   * @access public
   */
  public function __construct(Version $Version, ShipmentNumberType $ShipmentNumber)
  {
    $this->Version = $Version;
    $this->ShipmentNumber = $ShipmentNumber;
  }

}
