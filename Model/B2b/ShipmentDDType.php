<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class ShipmentDDType
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("shipment")
 */
class ShipmentDDType
{

  /**
   * 
   * @var ShipmentDetailsDDType $ShipmentDetails
   * @access public
   * @Type("Wk\DhlApiBundle\Model\B2b\ShipmentDetailsDDType")
   * @SerializedName("details")
   */
  public $ShipmentDetails = null;

  /**
   * 
   * @var ShipperDDType $Shipper
   * @access public
   * @Type("Wk\DhlApiBundle\Model\B2b\ShipperDDType")
   * @SerializedName("shipper")
   */
  public $Shipper = null;

  /**
   * 
   * @var ReceiverDDType $Receiver
   * @access public
   * @Type("Wk\DhlApiBundle\Model\B2b\ReceiverDDType")
   * @SerializedName("receiver")
   */
  public $Receiver = null;

  /**
   * 
   * @var ExportDocumentDDType $ExportDocument
   * @access public
   * @Type("Wk\DhlApiBundle\Model\B2b\ExportDocumentDDType")
   * @SerializedName("export")
   */
  public $ExportDocument = null;

  /**
   * 
   * @var IdentityType $Identity
   * @access public
   * @Type("Wk\DhlApiBundle\Model\B2b\IdentityType")
   * @SerializedName("identity")
   */
  public $Identity = null;

  /**
   * 
   * @var FurtherAddressesDDType $FurtherAddresses
   * @access public
   * @Type("Wk\DhlApiBundle\Model\B2b\FurtherAddressesDDType ")
   * @SerializedName("further_address")
   */
  public $FurtherAddresses = null;

  /**
   * 
   * @param ShipmentDetailsDDType $ShipmentDetails
   * @param ShipperDDType $Shipper
   * @param ReceiverDDType $Receiver
   * @param ExportDocumentDDType $ExportDocument
   * @param IdentityType $Identity
   * @param FurtherAddressesDDType $FurtherAddresses
   * @access public
   */
  public function __construct(ShipmentDetailsDDType $ShipmentDetails, ShipperDDType $Shipper, ReceiverDDType $Receiver, ExportDocumentDDType $ExportDocument = null, IdentityType $Identity = null, FurtherAddressesDDType $FurtherAddresses = null)
  {
    $this->ShipmentDetails = $ShipmentDetails;
    $this->Shipper = $Shipper;
    $this->Receiver = $Receiver;
    $this->ExportDocument = $ExportDocument;
    $this->Identity = $Identity;
    $this->FurtherAddresses = $FurtherAddresses;
  }

}
