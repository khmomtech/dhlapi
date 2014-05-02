<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\XmlRoot;

/**
 * Class NameType
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("name")
 */
class NameType
{

  /**
   * 
   * @var Person $Person
   * @access public
   * @Type("Wk\DhlApiBundle\Model\B2b\Person")
   * @SerializedName("person")
   */
  public $Person = null;

  /**
   * 
   * @var Company $Company
   * @access public
   * @Type("Wk\DhlApiBundle\Model\B2b\Company")
   * @SerializedName("company")
   */
  public $Company = null;

  /**
   * 
   * @param Person $Person
   * @param Company $Company
   * @access public
   */
  public function __construct($Person, $Company)
  {
    $this->Person = $Person;
    $this->Company = $Company;
  }

}
