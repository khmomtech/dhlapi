<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\Type;

/**
 * Class ShipperType
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("shipper")
 */
class ShipperType
{

    /**
     *
     * @var NameType $Company
     * @access public
     * @Type("Wk\DhlApiBundle\Model\B2b\NameType")
     * @SerializedName("company")
     */
    public $Company = null;

    /**
     *
     * @var NativeAddressType $Address
     * @access public
     * @Type("Wk\DhlApiBundle\Model\B2b\NativeAddressType")
     * @SerializedName("address")
     */
    public $Address = null;

    /**
     *
     * @var CommunicationType $Communication
     * @access public
     * @Type("Wk\DhlApiBundle\Model\B2b\CommunicationType")
     * @SerializedName("communication")
     */
    public $Communication = null;

    /**
     *
     * @var string $VAT
     * @access public
     * @Type("string")
     * @SerializedName("vat")
     */
    public $VAT = null;

    /**
     *
     * @param NameType $Company
     * @param NativeAddressType $Address
     * @param CommunicationType $Communication
     * @param string $VAT
     * @access public
     */
    public function __construct($Company, $Address, $Communication, $VAT)
    {
        $this->Company = $Company;
        $this->Address = $Address;
        $this->Communication = $Communication;
        $this->VAT = $VAT;
    }

}
