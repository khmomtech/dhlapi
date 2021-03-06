<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class CommunicationType
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("communication")
 */
class CommunicationType
{

  /**
   * 
   * @var string $phone
   * @access public
   * @Type("string")
   * @SerializedName("phone")
   * @XmlAttribute
   */
  public $phone = null;

  /**
   * 
   * @var string $email
   * @access public
   * @Type("string")
   * @SerializedName("email")
   * @XmlAttribute
   */
  public $email = null;

  /**
   * 
   * @var string $fax
   * @access public
   * @Type("string")
   * @SerializedName("fax")
   * @XmlAttribute
   */
  public $fax = null;

  /**
   * 
   * @var string $mobile
   * @access public
   * @Type("string")
   * @SerializedName("mobile")
   * @XmlAttribute
   */
  public $mobile = null;

  /**
   * 
   * @var string $internet
   * @access public
   * @Type("string")
   * @SerializedName("internet")
   * @XmlAttribute
   */
  public $internet = null;

  /**
   * 
   * @var string $contactPerson
   * @access public
   * @Type("string")
   * @SerializedName("contact")
   * @XmlAttribute
   */
  public $contactPerson = null;

  /**
   * 
   * @param string $phone
   * @param string $email
   * @param string $fax
   * @param string $mobile
   * @param string $internet
   * @param string $contactPerson
   * @access public
   */
  public function __construct($phone, $email, $fax, $mobile, $internet, $contactPerson)
  {
    $this->phone = $phone;
    $this->email = $email;
    $this->fax = $fax;
    $this->mobile = $mobile;
    $this->internet = $internet;
    $this->contactPerson = $contactPerson;
  }

}
