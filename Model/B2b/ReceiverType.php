<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class ReceiverType
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("receiver")
 */
class ReceiverType
{
    /**
     *
     * @var NameType $Company
     * @access public
     * @Type("Wk\DhlApiBundle\Model\B2b\NameType")
     * @SerializedName("name")
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
     * @var PackstationType $Packstation
     * @access public
     * @Type("Wk\DhlApiBundle\Model\B2b\PackstationType")
     * @SerializedName("packing_station")
     */
    public $Packstation = null;

    /**
     *
     * @var PostfilialeType $Postfiliale
     * @access public
     * @Type("Wk\DhlApiBundle\Model\B2b\PostfilialeType")
     * @SerializedName("post_office")
     */
    public $Postfiliale = null;

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
     * @XmlAttribute
     */
    public $VAT = null;

    /**
     *
     * @param NameType $Company
     * @param NativeAddressType $Address
     * @param CommunicationType $Communication
     * @param string $VAT
     * @param PackstationType $Packstation
     * @param PostfilialeType $Postfiliale
     * @access public
     */
    public function __construct($Company, $Address, $Communication, $VAT = null, $Packstation = null, $Postfiliale = null)
    {
        $this->Company = $Company;
        $this->Address = $Address;
        $this->Packstation = $Packstation;
        $this->Postfiliale = $Postfiliale;
        $this->Communication = $Communication;
        $this->VAT = $VAT;
    }

}
