<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\SerializedName;
use Wk\DhlApiBundle\Model\B2b\CountryType;
use Wk\DhlApiBundle\Model\B2b\ZipType;

/**
 * Class NativeAddressType
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("address")
 */
class NativeAddressType
{

  /**
   * 
   * @var string $streetName
   * @access public
   * @Type("string")
   * @SerializedName("street_name")
   * @XmlAttribute
   */
  public $streetName = null;

  /**
   * 
   * @var string $streetNumber
   * @access public
   * @Type("string")
   * @SerializedName("street_number")
   * @XmlAttribute
   */
  public $streetNumber = null;

  /**
   * 
   * @var string $careOfName
   * @access public
   * @Type("string")
   * @SerializedName("care_of_name")
   * @XmlAttribute
   */
  public $careOfName = null;

  /**
   * 
   * @var ZipType $Zip
   * @access public
   * @Type("Wk\DhlApiBundle\Model\B2b\ZipType")
   * @SerializedName("zip")
   */
  public $Zip = null;

  /**
   * 
   * @var string $city
   * @access public
   * @Type("string")
   * @SerializedName("city")
   * @XmlAttribute
   */
  public $city = null;

  /**
   * 
   * @var string $district
   * @access public
   * @Type("string")
   * @SerializedName("district")
   * @XmlAttribute
   */
  public $district = null;

  /**
   * 
   * @var CountryType $Origin
   * @access public
   * @Type("Wk\DhlApiBundle\Model\B2b\CountryType")
   * @SerializedName("country")
   */
  public $Origin = null;

  /**
   * 
   * @var string $floorNumber
   * @access public
   * @Type("string")
   * @SerializedName("floor")
   * @XmlAttribute
   */
  public $floorNumber = null;

  /**
   * 
   * @var string $roomNumber
   * @access public
   * @Type("string")
   * @SerializedName("room")
   * @XmlAttribute
   */
  public $roomNumber = null;

  /**
   * 
   * @var string $languageCodeISO
   * @access public
   * @Type("string")
   * @SerializedName("country_code")
   * @XmlAttribute
   */
  public $languageCodeISO = null;

  /**
   * 
   * @var string $note
   * @access public
   * @Type("string")
   * @SerializedName("note")
   * @XmlAttribute
   */
  public $note = null;

  /**
   * 
   * @param string $streetName
   * @param string $streetNumber
   * @param string $careOfName
   * @param ZipType $Zip
   * @param string $city
   * @param string $district
   * @param CountryType $Origin
   * @param string $floorNumber
   * @param string $roomNumber
   * @param string $languageCodeISO
   * @param string $note
   * @access public
   */
  public function __construct($streetName, $streetNumber, $careOfName, ZipType $Zip, $city, $district, CountryType $Origin, $floorNumber = null, $roomNumber = null, $languageCodeISO = 'DE', $note = null)
  {
    $this->streetName = $streetName;
    $this->streetNumber = $streetNumber;
    $this->careOfName = $careOfName;
    $this->Zip = $Zip;
    $this->city = $city;
    $this->district = $district;
    $this->Origin = $Origin;
    $this->floorNumber = $floorNumber;
    $this->roomNumber = $roomNumber;
    $this->languageCodeISO = $languageCodeISO;
    $this->note = $note;
  }

}
