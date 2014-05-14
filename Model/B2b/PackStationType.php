<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class PackstationType
 * @package Wk\DhlApiBundle\Model\B2b
 */
class PackstationType
{
    /**
     *
     * @var string $PackstationNumber
     * @access public
     * @Type("string")
     * @SerializedName("number")
     * @XmlAttribute
     */
    public $PackstationNumber = null;

    /**
     *
     * @var string $PostNumber
     * @access public
     * @Type("string")
     * @SerializedName("post_number")
     * @XmlAttribute
     */
    public $PostNumber = null;

    /**
     *
     * @var string $Zip
     * @access public
     * @Type("string")
     * @SerializedName("zip")
     * @XmlAttribute
     */
    public $Zip = null;

    /**
     *
     * @var string $City
     * @access public
     * @Type("string")
     * @SerializedName("city")
     * @XmlAttribute
     */
    public $City = null;

    /**
     *
     * @param string $PackstationNumber
     * @param string $PostNumber
     * @param string $Zip
     * @param string $City
     * @access public
     */
    public function __construct($PackstationNumber, $PostNumber, $Zip, $City)
    {
        $this->PackstationNumber = $PackstationNumber;
        $this->PostNumber = $PostNumber;
        $this->Zip = $Zip;
        $this->City = $City;
    }

}
