<?php

namespace Wk\DhlApiBundle\Model\B2b;

use DateTime;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class ShipmentDetailsTDType
 * @package Wk\DhlApiBundle\Model\B2b
 */
class ShipmentDetailsTDType extends ShipmentDetailsType
{
    /**
     *
     * @var Account $Account
     * @access public
     * @Type("Wk\DhlApiBundle\Model\B2b\Account")
     * @SerializedName("account")
     */
    public $Account = null;

    /**
     *
     * @var boolean $Dutiable
     * @access public
     * @Type("boolean")
     * @SerializedName("dutiable")
     * @XmlAttribute
     */
    public $Dutiable = null;

    /**
     *
     * @var string $DescriptionOfContent
     * @access public
     * @Type("string")
     * @SerializedName("description")
     * @XmlAttribute
     */
    public $DescriptionOfContent = null;

    /**
     *
     * @var Account $AccountPaidBy
     * @access public
     * @Type("Wk\DhlApiBundle\Model\B2b\Account")
     * @SerializedName("paid_by")
     */
    public $AccountPaidBy = null;

    /**
     *
     * @var string $ShipmentReference
     * @access public
     * @Type("string")
     * @SerializedName("reference")
     * @XmlAttribute
     */
    public $ShipmentReference = null;

    /**
     *
     * @var string $TermsOfTrade
     * @access public
     * @Type("string")
     * @SerializedName("terms")
     * @XmlAttribute
     */
    public $TermsOfTrade = null;

    /**
     *
     * @var ShipmentItemTDType $ShipmentItem
     * @access public
     * @Type("Wk\DhlApiBundle\Model\B2b\ShipmentItemDDType")
     * @SerializedName("item")
     */
    public $ShipmentItem = null;

    /**
     *
     * @var ShipmentServiceTDType $Service
     * @access public
     * @Type("Wk\DhlApiBundle\Model\B2b\ShipmentServiceTDType")
     * @SerializedName("service")
     */
    public $Service = null;

    /**
     *
     * @param string $ProductCode
     * @param DateTime $ShipmentDate
     * @param float $DeclaredValueOfGood
     * @param string $DeclaredValueOfGoodsCurrency
     * @param Account $Account
     * @param boolean $Dutiable
     * @param string $DescriptionOfContent
     * @param Account $AccountPaidBy
     * @param string $ShipmentReference
     * @param string $TermsOfTrade
     * @param ShipmentItemTDType $ShipmentItem
     * @param ShipmentServiceTDType $Service
     * @param ShipmentNotificationType $Notification
     * @param string $NotificationEmailText
     * @access public
     */
    public function __construct($ProductCode, DateTime $ShipmentDate, $DeclaredValueOfGood, $DeclaredValueOfGoodsCurrency, Account $Account, $Dutiable = false, $DescriptionOfContent, Account $AccountPaidBy, $ShipmentReference, $TermsOfTrade, ShipmentItemTDType $ShipmentItem, ShipmentServiceTDType $Service = null, ShipmentNotificationType $Notification = null, $NotificationEmailText = null)
    {
        parent::__construct($ProductCode, $ShipmentDate, $DeclaredValueOfGood, $DeclaredValueOfGoodsCurrency, $Notification, $NotificationEmailText);

        $this->Account = $Account;
        $this->Dutiable = $Dutiable;
        $this->DescriptionOfContent = $DescriptionOfContent;
        $this->AccountPaidBy = $AccountPaidBy;
        $this->ShipmentReference = $ShipmentReference;
        $this->TermsOfTrade = $TermsOfTrade;
        $this->ShipmentItem = $ShipmentItem;
        $this->Service = $Service;
    }

}
