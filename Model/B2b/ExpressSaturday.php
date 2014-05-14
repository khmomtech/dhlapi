<?php

namespace Wk\DhlApiBundle\Model\B2b;

class ExpressSaturday
{

  /**
   * 
   * @var ShippingDate $ShippingDate
   * @access public
   */
  public $ShippingDate = null;

  /**
   * 
   * @param ShippingDate $ShippingDate
   * @access public
   */
  public function __construct($ShippingDate)
  {
    $this->ShippingDate = $ShippingDate;
  }

}
