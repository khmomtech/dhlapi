<?php

namespace Wk\DhlApiBundle\Lib\B2b;

class ShipmentItemDDType extends ShipmentItemType
{
    /**
     * @var string $PackageType
     * @access public
     */
    public $PackageType = null;

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
