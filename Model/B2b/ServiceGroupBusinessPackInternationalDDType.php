<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class ServiceGroupBusinessPackInternationalDDType
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("bpi")
 */
class ServiceGroupBusinessPackInternationalDDType
{

  /**
   * 
   * @var boolean $Economy
   * @access public
   * @Type("boolean")
   * @XmlAttribute
   * @SerializedName("economy")
   */
  public $Economy = null;

  /**
   * 
   * @var boolean $Premium
   * @access public
   * @Type("boolean")
   * @XmlAttribute
   * @SerializedName("premium")
   */
  public $Premium = null;

  /**
   * 
   * @var boolean $Seapacket
   * @access public
   * @Type("boolean")
   * @XmlAttribute
   * @SerializedName("seapacket")
   */
  public $Seapacket = null;

  /**
   * 
   * @var boolean $CoilWithoutHelp
   * @access public
   * @Type("boolean")
   * @XmlAttribute
   * @SerializedName("coil")
   */
  public $CoilWithoutHelp = null;

  /**
   * 
   * @var Endorsement $Endorsement
   * @access public
   * @Type("Wk\DhlApiBundle\Model\B2b\Endorsement")
   * @SerializedName("endorsement")
   */
  public $Endorsement = null;

  /**
   * 
   * @var boolean $AmountInternational
   * @access public
   * @Type("boolean")
   * @XmlAttribute
   * @SerializedName("higher_insurance")
   */
  public $AmountInternational = null;

  /**
   * 
   * @param boolean $Economy
   * @param boolean $Premium
   * @param boolean $Seapacket
   * @param boolean $CoilWithoutHelp
   * @param Endorsement $Endorsement
   * @param boolean $AmountInternational
   * @access public
   */
  public function __construct($Economy = false, $Premium = false, $Seapacket = false, $CoilWithoutHelp = false, Endorsement $Endorsement = null, $AmountInternational = false)
  {
    $this->Economy = $Economy;
    $this->Premium = $Premium;
    $this->Seapacket = $Seapacket;
    $this->CoilWithoutHelp = $CoilWithoutHelp;
    $this->Endorsement = $Endorsement;
    $this->AmountInternational = $AmountInternational;
  }

}
