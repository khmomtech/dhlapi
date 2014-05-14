<?php

namespace Wk\DhlApiBundle\Model\B2b;

class ExportDocData
{

  /**
   * 
   * @var ShipmentNumberType $ShipmentNumber
   * @access public
   */
  public $ShipmentNumber = null;

  /**
   * 
   * @var StatusInformation $Status
   * @access public
   */
  public $Status = null;

  /**
   * 
   * @var string $ExportDocPDFData
   * @access public
   */
  public $ExportDocPDFData = null;

  /**
   * 
   * @var string $ExportDocURL
   * @access public
   */
  public $ExportDocURL = null;

  /**
   * 
   * @param ShipmentNumberType $ShipmentNumber
   * @param StatusInformation $Status
   * @param string $ExportDocPDFData
   * @param string $ExportDocURL
   * @access public
   */
  public function __construct($ShipmentNumber, $Status, $ExportDocPDFData, $ExportDocURL)
  {
    $this->ShipmentNumber = $ShipmentNumber;
    $this->Status = $Status;
    $this->ExportDocPDFData = $ExportDocPDFData;
    $this->ExportDocURL = $ExportDocURL;
  }

}
