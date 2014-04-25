<?php

namespace Wk\DhlApiBundle\Lib\B2b;

class PieceInformation
{

  /**
   * 
   * @var ShipmentNumberType $PieceNumber
   * @access public
   */
  public $PieceNumber = null;

  /**
   * 
   * @param ShipmentNumberType $PieceNumber
   * @access public
   */
  public function __construct($PieceNumber)
  {
    $this->PieceNumber = $PieceNumber;
  }

}
