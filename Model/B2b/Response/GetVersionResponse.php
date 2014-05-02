<?php

namespace Wk\DhlApiBundle\Model\B2b\Response;

use Wk\DhlApiBundle\Model\B2b\Version;

/**
 * Class GetVersionResponse
 * @package Wk\DhlApiBundle\Model\B2b\Response
 */
class GetVersionResponse
{
  /**
   * 
   * @var Version $Version
   * @access public
   */
  public $Version = null;

  /**
   * 
   * @param Version $Version
   * @access public
   */
  public function __construct(Version $Version)
  {
    $this->Version = $Version;
  }

}