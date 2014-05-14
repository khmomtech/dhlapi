<?php

namespace Wk\DhlApiBundle\Model\B2b\Request;

use Wk\DhlApiBundle\Model\B2b\Version;

/**
 * Class CancelPickupRequest
 * @package Wk\DhlApiBundle\Model\B2b\Request
 */
class CancelPickupRequest
{

  /**
   * 
   * @var Version $Version
   * @access public
   */
  public $Version = null;

  /**
   * 
   * @var string $BookingConfirmationNumber
   * @access public
   */
  public $BookingConfirmationNumber = null;

  /**
   * 
   * @param Version $Version
   * @param string $BookingConfirmationNumber
   * @access public
   */
  public function __construct(Version $Version, $BookingConfirmationNumber)
  {
    $this->Version = $Version;
    $this->BookingConfirmationNumber = $BookingConfirmationNumber;
  }

}
