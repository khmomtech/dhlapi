<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\XmlAttribute;
use JMS\Serializer\Annotation\Type;

/**
 * Class Bulkfreight
 *
 * @package Wk\DhlApiBundle\Model\B2b
 */
class Bulkfreight
{

	/**
	 *
	 * @var string $BulkfreightType
	 * @access public
	 * @SerializedName("type")
	 * @Type("string")
	 * @XmlAttribute
	 */
	public $BulkfreightType = null;

	/**
	 *
	 * @param string $BulkfreightType
	 * @access public
	 */
	public function __construct($BulkfreightType)
	{
		$this->BulkfreightType = $BulkfreightType;
	}

}
