<?php

namespace Wk\DhlApiBundle\Model\B2b\Request;

use Wk\DhlApiBundle\Model\B2b\Version;
use Wk\DhlApiBundle\Model\B2b\ShipmentNumberType;

/**
 * Class GetExportDocDDRequest
 *
 * @package Wk\DhlApiBundle\Model\B2b\Request
 */
class GetExportDocDDRequest
{

	/**
	 *
	 * @var Version $Version
	 * @access public
	 */
	public $Version = null;

	/**
	 *
	 * @var ShipmentNumberType $ShipmentNumber
	 * @access public
	 */
	public $ShipmentNumber = null;

	/**
	 *
	 * @var string $DocType
	 * @access public
	 */
	public $DocType = null;

	/**
	 * @param Version            $Version
	 * @param ShipmentNumberType $ShipmentNumber
	 * @param string             $DocType
	 *
	 * @access public
	 */
	public function __construct(Version $Version, ShipmentNumberType $ShipmentNumber, $DocType = 'PDF')
	{
		$this->Version = $Version;
		$this->ShipmentNumber = $ShipmentNumber;
		$this->DocType = $DocType;
	}

}
