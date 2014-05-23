<?php

namespace Wk\DhlApiBundle\Model\B2b\Response;

use Wk\DhlApiBundle\Model\B2b\Version;
use Wk\DhlApiBundle\Model\B2b\ManifestState;
use Wk\DhlApiBundle\Model\B2b\StatusInformation;

/**
 * Class DoManifestResponse
 * @package Wk\DhlApiBundle\Model\B2b\Response
 */
class DoManifestResponse
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
   * @var ManifestState $ManifestState
   * @access public
   */
  public $ManifestState = null;

  /**
   * 
   * @param Version $Version
   * @param Statusinformation $Status
   * @param ManifestState $ManifestState
   * @access public
   */
  public function __construct(Version $Version, StatusInformation $Status, ManifestState $ManifestState = null)
  {
    $this->Version = $Version;
    $this->Status = $Status;
    $this->ManifestState = $ManifestState;
  }

}
