<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class PostfilialeType
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("post_office")
 */
class PostfilialeType
{
    /**
     *
     * @var string $PostfilialNumber
     * @access public
     * @Type("string")
     * @SerializedName("number")
     * @XmlAttribute
     */
    public $PostfilialNumber = null;

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
     * @param string $PostfilialNumber
     * @param string $PostNumber
     * @param string $Zip
     * @param string $City
     * @access public
     */
    public function __construct($PostfilialNumber, $PostNumber, $Zip, $City)
    {
        $this->PostfilialNumber = $PostfilialNumber;
        $this->PostNumber = $PostNumber;
        $this->Zip = $Zip;
        $this->City = $City;
    }
}
