<?php

namespace Wk\DhlApiBundle\Model\B2b\Response;

use Wk\DhlApiBundle\Model\B2b\Version;
use Wk\DhlApiBundle\Model\B2b\StatusInformation;

/**
 * Class GetManifestDDResponse
 * @package Wk\DhlApiBundle\Model\B2b\Response
 */
class GetManifestDDResponse
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
   * @var string $ManifestPDFData
   * @access public
   */
  public $ManifestPDFData = null;

  /**
   * 
   * @param Version $Version
   * @param StatusInformation $status
   * @param string $ManifestPDFData
   * @access public
   */
  public function __construct(Version $Version, StatusInformation $status, $ManifestPDFData = null)
  {
    $this->Version = $Version;
    $this->status = $status;
    $this->ManifestPDFData = $ManifestPDFData;
  }

}
