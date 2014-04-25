<?php

namespace Wk\DhlApiBundle\Lib\B2b;

class ReceiverDDType
{

  /**
   * 
   * @var string $CompanyName3
   * @access public
   */
  public $CompanyName3 = null;

  /**
   * 
   * @param string $CompanyName3
   * @access public
   */
  public function __construct($CompanyName3)
  {
    $this->CompanyName3 = $CompanyName3;
  }

}
