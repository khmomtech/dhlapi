<?php

namespace Wk\DhlApiBundle\Model\B2b\Response;

use Wk\DhlApiBundle\Model\B2b\CreationState;
use Wk\DhlApiBundle\Model\B2b\StatusInformation;
use Wk\DhlApiBundle\Model\B2b\Version;

class CreateShipmentResponse
{

  /**
   * 
   * @var Version $Version
   * @access public
   */
  public $Version = null;

  /**
   * 
   * @var Statusinformation $status
   * @access public
   */
  public $status = null;

  /**
   * 
   * @var CreationState $CreationState
   * @access public
   */
  public $CreationState = null;

  /**
   * 
   * @param Version $Version
   * @param Statusinformation $status
   * @param CreationState $CreationState
   * @access public
   */
  public function __construct(Version $Version, StatusInformation $status, CreationState $CreationState = null)
  {
    $this->Version = $Version;
    $this->status = $status;
    $this->CreationState = $CreationState;
  }

}
