<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class PickupAddressType
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("address")
 */
class PickupAddressType
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
   * @param NativeAddressType $Address
   * @param CommunicationType $Communication
   * @access public
   */
  public function __construct(NameType $Company, NativeAddressType $Address, CommunicationType $Communication)
  {
    $this->Company = $Company;
    $this->Address = $Address;
    $this->Communication = $Communication;
  }

}
