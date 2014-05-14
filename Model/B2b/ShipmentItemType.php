<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class ShipmentItemType
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("item")
 */
class ShipmentItemType
{

  /**
   * 
   * @var double $WeightInKG
   * @access public
   * @Type("double")
   * @XmlAttribute
   * @SerializedName("weight")
   */
  public $WeightInKG = null;

  /**
   * 
   * @var double $LengthInCM
   * @access public
   * @Type("double")
   * @XmlAttribute
   * @SerializedName("length")
   */
  public $LengthInCM = null;

  /**
   * 
   * @var double $WidthInCM
   * @access public
   * @Type("double")
   * @XmlAttribute
   * @SerializedName("width")
   */
  public $WidthInCM = null;

  /**
   * 
   * @var double $HeightInCM
   * @access public
   * @Type("double")
   * @XmlAttribute
   * @SerializedName("height")
   */
  public $HeightInCM = null;

  /**
   * 
   * @param double $WeightInKG
   * @param double $LengthInCM
   * @param double $WidthInCM
   * @param double $HeightInCM
   * @access public
   */
  public function __construct($WeightInKG, $LengthInCM, $WidthInCM, $HeightInCM)
  {
    $this->WeightInKG = $WeightInKG;
    $this->LengthInCM = $LengthInCM;
    $this->WidthInCM = $WidthInCM;
    $this->HeightInCM = $HeightInCM;
  }

}
