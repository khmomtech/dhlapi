<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\Type;

/**
 * Class ShipperDDType
 * @package Wk\DhlApiBundle\Model\B2b
 */
class ShipperDDType extends ShipperType
{
    /**
     *
     * @var string $Remark
     * @access public
     * @Type("string")
     * @XmlAttribute
     * @SerializedName("remark")
     */
    public $Remark = null;

    /**
     * @param NameType $Company
     * @param NativeAddressType $Address
     * @param CommunicationType $Communication
     * @param string $VAT
     * @param string $Remark
     * @access public
     */
    public function __construct($Company, $Address, $Communication, $VAT = null, $Remark = null)
    {
        parent::__construct($Company, $Address, $Communication, $VAT);

        $this->Remark = $Remark;
    }
}
