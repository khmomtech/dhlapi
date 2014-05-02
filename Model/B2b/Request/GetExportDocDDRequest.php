<?php

namespace Wk\DhlApiBundle\Model\B2b\Request;

class GetExportDocDDRequest
{

  /**
   * 
   * @var Version $Version
   * @access public
   */
  public $Version = null;

  /**
   * 
   * @var ShipmentNumberType $ShipmentNumber
   * @access public
   */
  public $ShipmentNumber = null;

  /**
   * 
   * @var ExportDocumentDDType $DocType
   * @access public
   */
  public $DocType = null;

  /**
   * 
   * @param Version $Version
   * @param ShipmentNumberType $ShipmentNumber
   * @param ExportDocumentDDType $DocType
   * @access public
   */
  public function __construct($Version, $ShipmentNumber, $DocType)
  {
    $this->Version = $Version;
    $this->ShipmentNumber = $ShipmentNumber;
    $this->DocType = $DocType;
  }

}
