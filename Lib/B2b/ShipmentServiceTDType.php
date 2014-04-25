<?php

namespace Wk\DhlApiBundle\Lib\B2b;

class ShipmentServiceTDType
{

  /**
   * 
   * @var ServiceGroupDateTimeOptionTDType $ServiceGroupDateTimeOption
   * @access public
   */
  public $ServiceGroupDateTimeOption = null;

  /**
   * 
   * @var ServiceGroupOtherTDType $ServiceGroupOther
   * @access public
   */
  public $ServiceGroupOther = null;

  /**
   * 
   * @param ServiceGroupDateTimeOptionTDType $ServiceGroupDateTimeOption
   * @param ServiceGroupOtherTDType $ServiceGroupOther
   * @access public
   */
  public function __construct($ServiceGroupDateTimeOption, $ServiceGroupOther)
  {
    $this->ServiceGroupDateTimeOption = $ServiceGroupDateTimeOption;
    $this->ServiceGroupOther = $ServiceGroupOther;
  }

}
