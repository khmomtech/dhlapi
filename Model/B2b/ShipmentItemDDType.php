<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class ShipmentItemDDType
 * @package Wk\DhlApiBundle\Model\B2b
 */
class ShipmentItemDDType extends ShipmentItemType
{
    /**
     * @var string $PackageType
     * @access public
     * @Type("string")
     * @XmlAttribute
     * @SerializedName("package_type")
     */
    public $PackageType = 'PK';

    /**
     *
     * @param string $PackageType
     * @param float $WeightInKG
     * @param float $LengthInCM
     * @param float $WidthInCM
     * @param float $HeightInCM
     * @access public
     */
    public function __construct($WeightInKG, $LengthInCM, $WidthInCM, $HeightInCM, $PackageType = 'PK')
    {
        parent::__construct($WeightInKG, $LengthInCM, $WidthInCM, $HeightInCM);
        $this->PackageType = $PackageType;
    }

}
