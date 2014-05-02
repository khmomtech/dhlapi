<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class FurtherAddressesDDType
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("further")
 */
class FurtherAddressesDDType
{

  /**
   * 
   * @var DeliveryAddress $DeliveryAdress
   * @access public
   * @Type("Wk\DhlApiBundle\Model\B2b\DeliveryAddress")
   * @SerializedName("address")
   */
  public $DeliveryAdress = null;

  /**
   * 
   * @param DeliveryAddress $DeliveryAddress
   * @access public
   */
  public function __construct(DeliveryAddress $DeliveryAddress)
  {
    $this->DeliveryAdress = $DeliveryAddress;
  }

}
