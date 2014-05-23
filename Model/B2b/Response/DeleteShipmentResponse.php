<?php

namespace Wk\DhlApiBundle\Model\B2b\Response;

use Wk\DhlApiBundle\Model\B2b\Version;
use Wk\DhlApiBundle\Model\B2b\DeletionState;
use Wk\DhlApiBundle\Model\B2b\StatusInformation;

/**
 * Class DeleteShipmentResponse
 * @package Wk\DhlApiBundle\Model\B2b\Response
 */
class DeleteShipmentResponse
{

  /**
   * 
   * @var Version $Version
   * @access public
   */
  public $Version = null;

  /**
   * 
   * @var Statusinformation $Status
   * @access public
   */
  public $Status = null;

  /**
   * 
   * @var DeletionState $DeletionState
   * @access public
   */
  public $DeletionState = null;

  /**
   * 
   * @param Version $Version
   * @param Statusinformation $Status
   * @param DeletionState $DeletionState
   * @access public
   */
  public function __construct(Version $Version, StatusInformation $Status, DeletionState $DeletionState = null)
  {
    $this->Version = $Version;
    $this->Status = $Status;
    $this->DeletionState = $DeletionState;
  }

}
