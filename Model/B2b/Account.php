<?php

namespace Wk\DhlApiBundle\Model\B2b;

class Account
{

  /**
   * 
   * @var accountNumberExpress $accountNumberExpress
   * @access public
   */
  public $accountNumberExpress = null;

  /**
   * 
   * @param accountNumberExpress $accountNumberExpress
   * @access public
   */
  public function __construct($accountNumberExpress)
  {
    $this->accountNumberExpress = $accountNumberExpress;
  }

}
