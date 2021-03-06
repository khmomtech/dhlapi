<?php

namespace Wk\DhlApiBundle\Model\B2b;

class DeletionState
{

  /**
   * 
   * @var ShipmentNumberType $ShipmentNumber
   * @access public
   */
  public $ShipmentNumber = null;

  /**
   * 
   * @var StatusInformation $Status
   * @access public
   */
  public $Status = null;

  /**
   * 
   * @param ShipmentNumberType $ShipmentNumber
   * @param StatusInformation $Status
   * @access public
   */
  public function __construct($ShipmentNumber, $Status)
  {
    $this->ShipmentNumber = $ShipmentNumber;
    $this->Status = $Status;
  }

}
