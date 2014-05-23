<?php

namespace Wk\DhlApiBundle\Model\B2b\Response;

use Wk\DhlApiBundle\Model\B2b\Version;
use Wk\DhlApiBundle\Model\B2b\StatusInformation;
use Wk\DhlApiBundle\Model\B2b\ExportDocData;

/**
 * Class GetExportDocResponse
 * @package Wk\DhlApiBundle\Model\B2b\Response
 */
class GetExportDocResponse
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
   * @var ExportDocData $ExportDocData
   * @access public
   */
  public $ExportDocData = null;

  /**
   * 
   * @param Version $Version
   * @param Statusinformation $status
   * @param ExportDocData $ExportDocData
   * @access public
   */
  public function __construct(Version $Version, StatusInformation $status, ExportDocData $ExportDocData = null)
  {
    $this->Version = $Version;
    $this->status = $status;
    $this->ExportDocData = $ExportDocData;
  }

}
