<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class BankType
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("bank")
 */
class BankType
{

  /**
   * 
   * @var string $accountOwner
   * @access public
   * @Type("string")
   * @XmlAttribute
   * @SerializedName("owner")
   */
  public $accountOwner = null;

  /**
   * 
   * @var string $accountNumber
   * @access public
   * @Type("string")
   * @XmlAttribute
   * @SerializedName("account")
   */
  public $accountNumber = null;

  /**
   * 
   * @var string $bankCode
   * @access public
   * @Type("string")
   * @XmlAttribute
   * @SerializedName("code")
   */
  public $bankCode = null;

  /**
   * 
   * @var string $bankName
   * @access public
   * @Type("string")
   * @XmlAttribute
   * @SerializedName("name")
   */
  public $bankName = null;

  /**
   * 
   * @var string $iban
   * @access public
   * @Type("string")
   * @XmlAttribute
   * @SerializedName("iban")
   */
  public $iban = null;

  /**
   * 
   * @var string $note
   * @access public
   * @Type("string")
   * @XmlAttribute
   * @SerializedName("note")
   */
  public $note = null;

  /**
   * 
   * @var string $bic
   * @access public
   * @Type("string")
   * @XmlAttribute
   * @SerializedName("bic")
   */
  public $bic = null;

  /**
   * 
   * @param string $accountOwner
   * @param string $accountNumber
   * @param string $bankCode
   * @param string $bankName
   * @param string $iban
   * @param string $note
   * @param string $bic
   * @access public
   */
  public function __construct($accountOwner, $accountNumber, $bankCode, $bankName, $iban, $note, $bic)
  {
    $this->accountOwner = $accountOwner;
    $this->accountNumber = $accountNumber;
    $this->bankCode = $bankCode;
    $this->bankName = $bankName;
    $this->iban = $iban;
    $this->note = $note;
    $this->bic = $bic;
  }

}
