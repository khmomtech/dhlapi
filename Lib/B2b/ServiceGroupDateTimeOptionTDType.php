<?php

namespace Wk\DhlApiBundle\Lib\B2b;

class ServiceGroupDateTimeOptionTDType
{

  /**
   * 
   * @var ExpressSaturday $ExpressSaturday
   * @access public
   */
  public $ExpressSaturday = null;

  /**
   * 
   * @param ExpressSaturday $ExpressSaturday
   * @access public
   */
  public function __construct($ExpressSaturday)
  {
    $this->ExpressSaturday = $ExpressSaturday;
  }

}
