<?php

namespace Wk\DhlApiBundle\Lib\B2b;

class AccountPaidBy
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
