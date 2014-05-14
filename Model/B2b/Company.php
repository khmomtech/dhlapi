<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlAttribute;

/**
 * Class Company
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("company")
 */
class Company
{

  /**
   * 
   * @var string $name1
   * @access public
   * @Type("string")
   * @SerializedName("name")
   * @XmlAttribute
   */
  public $name1 = null;

  /**
   * 
   * @var string $name2
   * @access public
   * @Type("string")
   * @SerializedName("department")
   * @XmlAttribute
   */
  public $name2 = null;

  /**
   * 
   * @param string $name
   * @param string $department
   * @access public
   */
  public function __construct($name, $department)
  {
    $this->name1 = $name;
    $this->name2 = $department;
  }

}
