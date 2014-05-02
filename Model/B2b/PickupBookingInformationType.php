<?php

namespace Wk\DhlApiBundle\Model\B2b;

use DateTime;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\XmlRoot;

/**
 * Class PickupBookingInformationType
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("information")
 */
class PickupBookingInformationType
{

  /**
   * 
   * @var string $ProductID
   * @access public
   * @Type("string")
   * @SerializedName("product")
   * @XmlAttribute
   */
  public $ProductID = null;

  /**
   * 
   * @var string $Account
   * @access public
   * @Type("string")
   * @SerializedName("account")
   * @XmlAttribute
   */
  public $Account = null;

  /**
   * 
   * @var string $Attendance
   * @access public
   * @Type("string")
   * @SerializedName("attendance")
   * @XmlAttribute
   */
  public $Attendance = null;

  /**
   * 
   * @var string $PickupDate
   * @access public
   * @Type("string")
   * @SerializedName("date")
   * @XmlAttribute
   */
  public $PickupDate = null;

  /**
   * 
   * @var string $ReadyByTime
   * @access public
   * @Type("string")
   * @SerializedName("ready_by_time")
   * @XmlAttribute
   */
  public $ReadyByTime = null;

  /**
   * 
   * @var string $ClosingTime
   * @access public
   * @Type("string")
   * @SerializedName("closing_time")
   * @XmlAttribute
   */
  public $ClosingTime = null;

  /**
   * 
   * @var string $Remark
   * @access public
   * @Type("string")
   * @SerializedName("remark")
   * @XmlAttribute
   */
  public $Remark = null;

  /**
   * 
   * @var string $PickupLocation
   * @access public
   * @Type("string")
   * @SerializedName("location")
   * @XmlAttribute
   */
  public $PickupLocation = null;

  /**
   * 
   * @var int $AmountOfPieces
   * @access public
   * @Type("integer")
   * @SerializedName("pieces")
   * @XmlAttribute
   */
  public $AmountOfPieces = null;

  /**
   * 
   * @var int $AmountOfPallets
   * @access public
   * @Type("integer")
   * @SerializedName("pallets")
   * @XmlAttribute
   */
  public $AmountOfPallets = null;

  /**
   * 
   * @var double $WeightInKG
   * @access public
   * @Type("double")
   * @SerializedName("weight")
   * @XmlAttribute
   */
  public $WeightInKG = null;

  /**
   * 
   * @var int $CountOfShipments
   * @access public
   * @Type("integer")
   * @SerializedName("shipments")
   * @XmlAttribute
   */
  public $CountOfShipments = null;

  /**
   * 
   * @var double $TotalVolumeWeight
   * @access public
   * @Type("double")
   * @SerializedName("total_weight")
   * @XmlAttribute
   */
  public $TotalVolumeWeight = null;

  /**
   * 
   * @var double $MaxLengthInCM
   * @access public
   * @Type("double")
   * @SerializedName("max_length")
   * @XmlAttribute
   */
  public $MaxLengthInCM = null;

  /**
   * 
   * @var double $MaxWidthInCM
   * @access public
   * @Type("double")
   * @SerializedName("max_width")
   * @XmlAttribute
   */
  public $MaxWidthInCM = null;

  /**
   * 
   * @var double $MaxHeightInCM
   * @access public
   * @Type("double")
   * @SerializedName("max_height")
   * @XmlAttribute
   */
  public $MaxHeightInCM = null;

  /**
   * 
   * @param string $ProductID
   * @param string $Account
   * @param string $Attendance
   * @param DateTime $ReadyByTime
   * @param DateTime $ClosingTime
   * @param string $Remark
   * @param string $PickupLocation
   * @param int $AmountOfPieces
   * @param int $AmountOfPallets
   * @param double $WeightInKG
   * @param int $CountOfShipments
   * @param double $TotalVolumeWeight
   * @param double $MaxLengthInCM
   * @param double $MaxWidthInCM
   * @param double $MaxHeightInCM
   * @access public
   */
  public function __construct($ProductID, $Account, $Attendance, DateTime $ReadyByTime, DateTime $ClosingTime, $Remark, $PickupLocation, $AmountOfPieces, $AmountOfPallets, $WeightInKG, $CountOfShipments, $TotalVolumeWeight, $MaxLengthInCM, $MaxWidthInCM, $MaxHeightInCM)
  {
    $this->ProductID = $ProductID;
    $this->Account = $Account;
    $this->Attendance = $Attendance;
    $this->PickupDate = $ReadyByTime->format('Y-m-d');
    $this->ReadyByTime = $ReadyByTime->format('H:i');
    $this->ClosingTime = $ClosingTime->format('H:i');
    $this->Remark = $Remark;
    $this->PickupLocation = $PickupLocation;
    $this->AmountOfPieces = $AmountOfPieces;
    $this->AmountOfPallets = $AmountOfPallets;
    $this->WeightInKG = $WeightInKG;
    $this->CountOfShipments = $CountOfShipments;
    $this->TotalVolumeWeight = $TotalVolumeWeight;
    $this->MaxLengthInCM = $MaxLengthInCM;
    $this->MaxWidthInCM = $MaxWidthInCM;
    $this->MaxHeightInCM = $MaxHeightInCM;
  }

}
