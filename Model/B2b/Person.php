<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlAttribute;

/**
 * Class Person
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("person")
 */
class Person
{

  /**
   * 
   * @var string $salutation
   * @access public
   * @Type("string")
   * @XmlAttribute
   */
  public $salutation = null;

  /**
   * 
   * @var string $title
   * @access public
   * @Type("string")
   * @XmlAttribute
   */
  public $title = null;

  /**
   * 
   * @var string $firstname
   * @access public
   * @Type("string")
   * @XmlAttribute
   */
  public $firstname = null;

  /**
   * 
   * @var string $middlename
   * @access public
   * @Type("string")
   * @XmlAttribute
   */
  public $middlename = null;

  /**
   * 
   * @var string $lastname
   * @access public
   * @Type("string")
   * @XmlAttribute
   */
  public $lastname = null;

  /**
   * 
   * @param string $salutation
   * @param string $title
   * @param string $firstname
   * @param string $middlename
   * @param string $lastname
   * @access public
   */
  public function __construct($salutation, $title, $firstname, $middlename, $lastname)
  {
    $this->salutation = $salutation;
    $this->title = $title;
    $this->firstname = $firstname;
    $this->middlename = $middlename;
    $this->lastname = $lastname;
  }

}
