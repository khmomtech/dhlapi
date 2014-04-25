<?php

namespace Wk\DhlApiBundle\Lib\B2b;

class AuthentificationType
{

  /**
   * 
   * @var user $user
   * @access public
   */
  public $user = null;

  /**
   * 
   * @var signature $signature
   * @access public
   */
  public $signature = null;

  /**
   * 
   * @var accountNumber $accountNumber
   * @access public
   */
  public $accountNumber = null;

  /**
   * 
   * @var int $type
   * @access public
   */
  public $type = 0;

  /**
   * 
   * @param user $user
   * @param signature $signature
   * @param accountNumber $accountNumber
   * @param int $type
   * @access public
   */
  public function __construct($user, $signature, $accountNumber, $type = 0)
  {
    $this->user = $user;
    $this->signature = $signature;
    $this->accountNumber = $accountNumber;
    $this->type = $type;
  }

}
