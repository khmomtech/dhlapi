<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlValue;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class Account
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("account")
 */
class Account
{
	/**
	 *
	 * @var string $accountNumberExpress
	 * @access public
	 * @Type("string")
	 * @SerializedName("number")
	 * @XmlValue
	 */
	public $accountNumberExpress = null;

	/**
	 *
	 * @param string $accountNumberExpress
	 * @access public
	 */
	public function __construct($accountNumberExpress)
	{
		$this->accountNumberExpress = $accountNumberExpress;
	}
}
