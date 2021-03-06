<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\XmlValue;
use JMS\Serializer\Annotation\SerializedName;

/**
 * Class Attendance
 * @package Wk\DhlApiBundle\Model\B2b
 * @XmlRoot("attendance")
 */
class Attendance
{

	/**
	 *
	 * @var string $partnerID
	 * @access public
	 * @Type("string")
	 * @SerializedName("id")
	 * @XmlValue
	 */
	public $partnerID = null;

	/**
	 *
	 * @param string $partnerID
	 * @access public
	 */
	public function __construct($partnerID)
	{
		$this->partnerID = $partnerID;
	}

}
