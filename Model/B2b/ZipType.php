<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class ZipType
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("zip")
 */
class ZipType
{

  /**
   * 
   * @var string $germany
   * @access public
   * @Type("string")
   * @SerializedName("germany")
   * @XmlAttribute
   */
  public $germany = null;

  /**
   * 
   * @var string $england
   * @access public
   * @Type("string")
   * @SerializedName("england")
   * @XmlAttribute
   */
  public $england = null;

  /**
   * 
   * @var string $other
   * @access public
   * @Type("string")
   * @SerializedName("other")
   * @XmlAttribute
   */
  public $other = null;

  /**
   * 
   * @param string $germany
   * @param string $england
   * @param string $other
   * @access public
   */
  public function __construct($germany, $england = null, $other = null) {
    $this->germany = $germany;
    $this->england = $england;
    $this->other = $other;
  }

}
