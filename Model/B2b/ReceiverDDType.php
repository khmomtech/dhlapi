<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class ReceiverDDType
 * @package Wk\DhlApiBundle\Model\B2b
 */
class ReceiverDDType extends ReceiverType
{
    /**
     *
     * @var string $CompanyName3
     * @access public
     * @Type("string")
     * @XmlAttribute
     * @SerializedName("name")
     */
    public $CompanyName3 = null;

    /**
     * @param NameType $Company
     * @param NativeAddressType $Address
     * @param PackstationType $Packstation
     * @param PostfilialeType $Postfiliale
     * @param CommunicationType $Communication
     * @param string $VAT
     * @param string $CompanyName3
     * @access public
     */
    public function __construct($Company, $Address, $Packstation, $Postfiliale, $Communication, $VAT, $CompanyName3)
    {
        parent::__construct($Company, $Address, $Packstation, $Postfiliale, $Communication, $VAT);

        $this->CompanyName3 = $CompanyName3;
    }

}
