<?php

namespace Wk\DhlApiBundle\Model\B2b;

use DateTime;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class ShipmentDetailsType
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("details")
 */
class ShipmentDetailsType
{
    /**
     *
     * @var string $ProductCode
     * @access public
     * @Type("string")
     * @XmlAttribute
     * @SerializedName("product_code")
     */
    public $ProductCode = 'EPN';

    /**
     *
     * @var string $ShipmentDate
     * @access public
     * @Type("string")
     * @XmlAttribute
     * @SerializedName("date")
     */
    public $ShipmentDate = null;

    /**
     *
     * @var double $DeclaredValueOfGoods
     * @access public
     * @Type("double")
     * @XmlAttribute
     * @SerializedName("goods_value")
     */
    public $DeclaredValueOfGoods = null;

    /**
     *
     * @var string $DeclaredValueOfGoodsCurrency
     * @access public
     * @Type("string")
     * @XmlAttribute
     * @SerializedName("currency")
     */
    public $DeclaredValueOfGoodsCurrency = null;

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
     */
    public $NotificationEmailText = null;

    /**
     *
     * @param string $ProductCode
     * @param DateTime $ShipmentDate
     * @param double $DeclaredValueOfGoods
     * @param string $DeclaredValueOfGoodsCurrency
     * @param ShipmentNotificationType $Notification
     * @param string $NotificationEmailText
     * @access public
     */
    public function __construct($ProductCode = 'EPN', DateTime $ShipmentDate, $DeclaredValueOfGoods, $DeclaredValueOfGoodsCurrency = 'EUR', ShipmentNotificationType $Notification = null, $NotificationEmailText = null)
    {
        $this->ProductCode = $ProductCode;
        $this->ShipmentDate = $ShipmentDate->format('Y-m-d');
        $this->DeclaredValueOfGoods = $DeclaredValueOfGoods;
        $this->DeclaredValueOfGoodsCurrency = $DeclaredValueOfGoodsCurrency;
        $this->Notification = $Notification;
        $this->NotificationEmailText = $NotificationEmailText;
    }
}
