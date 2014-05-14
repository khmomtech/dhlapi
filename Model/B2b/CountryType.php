<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class CountryType
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("country")
 */
class CountryType
{

  /**
   * 
   * @var string $country
   * @access public
   * @Type("string")
   * @SerializedName("name")
   * @XmlAttribute
   */
  public $country = null;

  /**
   * 
   * @var string $countryISOCode
   * @access public
   * @Type("string")
   * @SerializedName("code")
   * @XmlAttribute
   */
  public $countryISOCode = null;

  /**
   * 
   * @var string $state
   * @access public
   * @Type("string")
   * @SerializedName("state")
   * @XmlAttribute
   */
  public $state = null;

  /**
   * 
   * @param string $country
   * @param string $countryISOCode
   * @param string $state
   * @access public
   */
  public function __construct($country, $countryISOCode, $state)
  {
    $this->country = $country;
    $this->countryISOCode = $countryISOCode;
    $this->state = $state;
  }

}
