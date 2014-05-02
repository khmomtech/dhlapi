<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\SerializedName;

class ShipmentDetailsDDType
{

  /**
   * 
   * @var string $EKP
   * @access public
   * @Type("string")
   * @SerializedName("ekp")
   * @XmlAttribute
   */
  public $EKP = null;

  /**
   * 
   * @var Attendance $Attendance
   * @access public
   * @Type("Wk\DhlApiBundle\Model\B2b\Attendance")
   * @SerializedName("attendance")
   */
  public $Attendance = null;

  /**
   * 
   * @var string $CustomerReference
   * @access public
   * @Type("string")
   * @SerializedName("reference")
   * @XmlAttribute
   */
  public $CustomerReference = null;

  /**
   * 
   * @var string $Description
   * @access public
   * @Type("string")
   * @SerializedName("description")
   * @XmlAttribute
   */
  public $Description = null;

  /**
   * 
   * @var string $DeliveryRemarks
   * @access public
   * @Type("string")
   * @SerializedName("remarks")
   * @XmlAttribute
   */
  public $DeliveryRemarks = null;

  /**
   * 
   * @var ShipmentItemDDType $ShipmentItem
   * @access public
   * @Type("Wk\DhlApiBundle\Model\B2b\ShipmentItemDDType")
   * @SerializedName("item")
   */
  public $ShipmentItem = null;

  /**
   * 
   * @var ShipmentServiceDDType $Service
   * @access public
   * @Type("Wk\DhlApiBundle\Model\B2b\ShipmentServiceDDType")
   * @SerializedName("service")
   */
  public $Service = null;

  /**
   * 
   * @var ShipmentNotificationType $Notification
   * @access public
   * @Type("Wk\DhlApiBundle\Model\B2b\ShipmentNotificationType")
   * @SerializedName("notification")
   */
  public $Notification = null;

  /**
   * 
   * @var string $NotificationEmailText
   * @access public
   * @Type("string")
   * @SerializedName("notification_text")
   * @XmlAttribute
   */
  public $NotificationEmailText = null;

  /**
   * 
   * @var BankType $BankData
   * @access public
   * @Type("Wk\DhlApiBundle\Model\B2b\BankType")
   * @SerializedName("bank")
   */
  public $BankData = null;

  /**
   * 
   * @param string $EKP
   * @param Attendance $Attendance
   * @param string $CustomerReference
   * @param string $Description
   * @param string $DeliveryRemarks
   * @param ShipmentItemDDType $ShipmentItem
   * @param ShipmentServiceDDType $Service
   * @param ShipmentNotificationType $Notification
   * @param string $NotificationEmailText
   * @param BankType $BankData
   * @access public
   */
  public function __construct($EKP, $Attendance, $CustomerReference, $Description, $DeliveryRemarks, $ShipmentItem, $Service, $Notification, $NotificationEmailText, $BankData)
  {
    $this->EKP = $EKP;
    $this->Attendance = $Attendance;
    $this->CustomerReference = $CustomerReference;
    $this->Description = $Description;
    $this->DeliveryRemarks = $DeliveryRemarks;
    $this->ShipmentItem = $ShipmentItem;
    $this->Service = $Service;
    $this->Notification = $Notification;
    $this->NotificationEmailText = $NotificationEmailText;
    $this->BankData = $BankData;
  }

}
