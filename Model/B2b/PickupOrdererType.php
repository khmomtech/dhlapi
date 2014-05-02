<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class PickupOrdererType
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("orderer")
 */
class PickupOrdererType extends PickupAddressType
{
    /**
     *
     * @var string $Name3
     * @access public
     * @Type("string")
     * @SerializedName("name")
     * @XmlAttribute
     */
    public $Name3 = null;

    /**
     *
     * @param NameType $Company
     * @param NativeAddressType $Address
     * @param CommunicationType $Communication
     * @param string $Name3
     * @access public
     */
    public function __construct(NameType $Company, NativeAddressType $Address, CommunicationType $Communication, $Name3)
    {
        parent::__construct($Company, $Address, $Communication);

        $this->Name3 = $Name3;
    }
}
