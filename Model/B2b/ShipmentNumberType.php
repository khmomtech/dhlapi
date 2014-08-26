<?php

namespace Wk\DhlApiBundle\Model\B2b;

/**
 * Class ShipmentNumberType
 *
 * @package Wk\DhlApiBundle\Model\B2b
 */
class ShipmentNumberType
{
  /**
   * 
   * @var string $identCode
   * @access public
   */
  public $identCode = null;

  /**
   * 
   * @var string $licensePlate
   * @access public
   */
  public $licensePlate = null;

  /**
   * 
   * @var string $airwayBill
   * @access public
   */
  public $airwayBill = null;

  /**
   * 
   * @var string $shipmentNumber
   * @access public
   */
  public $shipmentNumber = null;

  /**
   * 
   * @param string $identCode
   * @param string $licensePlate
   * @param string $airwayBill
   * @param string $shipmentNumber
   * @access public
   */
  public function __construct($identCode = null, $licensePlate = null, $airwayBill = null, $shipmentNumber = null)
  {
    $this->identCode = $identCode;
    $this->licensePlate = $licensePlate;
    $this->airwayBill = $airwayBill;
    $this->shipmentNumber = $shipmentNumber;
  }

}
