<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class DeliveryAddress
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("address")
 */
class DeliveryAddress
{

  /**
   * 
   * @var NameType $Company
   * @access public
   * @Type("Wk\DhlApiBundle\Model\B2b\NameType")
   * @SerializedName("company")
   */
  public $Company = null;

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
   * @var NativeAddressType $Address
   * @access public
   * @Type("Wk\DhlApiBundle\Model\B2b\NativeAddressType")
   * @SerializedName("address")
   */
  public $Address = null;

  /**
   * 
   * @var CommunicationType $Communication
   * @access public
   * @Type("Wk\DhlApiBundle\Model\B2b\CommunicationType")
   * @SerializedName("communication")
   */
  public $Communication = null;

  /**
   * 
   * @param NameType $Company
   * @param string $Name3
   * @param NativeAddressType $Address
   * @param CommunicationType $Communication
   * @access public
   */
  public function __construct($Company, $Name3, $Address, $Communication)
  {
    $this->Company = $Company;
    $this->Name3 = $Name3;
    $this->Address = $Address;
    $this->Communication = $Communication;
  }

}
