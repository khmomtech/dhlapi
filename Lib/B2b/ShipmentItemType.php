<?php

namespace Wk\DhlApiBundle\Lib\B2b;

class ShipmentItemType
{

  /**
   * 
   * @var WeightInKG $WeightInKG
   * @access public
   */
  public $WeightInKG = null;

  /**
   * 
   * @var LengthInCM $LengthInCM
   * @access public
   */
  public $LengthInCM = null;

  /**
   * 
   * @var WidthInCM $WidthInCM
   * @access public
   */
  public $WidthInCM = null;

  /**
   * 
   * @var HeightInCM $HeightInCM
   * @access public
   */
  public $HeightInCM = null;

  /**
   * 
   * @param float $WeightInKG
   * @param float $LengthInCM
   * @param float $WidthInCM
   * @param float $HeightInCM
   * @access public
   */
  public function __construct($WeightInKG, $LengthInCM, $WidthInCM, $HeightInCM)
  {
    $this->WeightInKG = $WeightInKG;
    $this->LengthInCM = $LengthInCM;
    $this->WidthInCM = $WidthInCM;
    $this->HeightInCM = $HeightInCM;
  }

}
