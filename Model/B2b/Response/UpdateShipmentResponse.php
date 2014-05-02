<?php

namespace Wk\DhlApiBundle\Model\B2b\Response;

use Wk\DhlApiBundle\Model\B2b\Version;
use Wk\DhlApiBundle\Model\B2b\StatusInformation;
use Wk\DhlApiBundle\Model\B2b\CreationState;

/**
 * Class UpdateShipmentResponse
 * @package Wk\DhlApiBundle\Model\B2b\Response
 */
class UpdateShipmentResponse
{

  /**
   * 
   * @var Version $Version
   * @access public
   */
  public $Version = null;

  /**
   * 
   * @var StatusInformation $status
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
   * @param StatusInformation $status
   * @param CreationState $CreationState
   * @access public
   */
  public function __construct(Version $Version, StatusInformation $status, CreationState $CreationState)
  {
    $this->Version = $Version;
    $this->status = $status;
    $this->CreationState = $CreationState;
  }

}