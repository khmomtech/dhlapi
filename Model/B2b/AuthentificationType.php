<?php

namespace Wk\DhlApiBundle\Model\B2b;

class AuthentificationType
{
	/**
	 *
	 * @var string $user
	 * @access public
	 */
	public $user = null;

	/**
	 *
	 * @var string $signature
	 * @access public
	 */
	public $signature = null;

	/**
	 *
	 * @var string $accountNumber
	 * @access public
	 */
	public $accountNumber = null;

	/**
	 *
	 * @var int $type
	 * @access public
	 */
	public $type = 0;

	/**
	 * Class constructor
	 *
	 * @param string $user
	 * @param string $signature
	 * @param string $accountNumber
	 * @param int $type
	 * @access public
	 */
	public function __construct($user, $signature, $accountNumber = null, $type = 0)
	{
		$this->user = $user;
		$this->signature = $signature;
		$this->accountNumber = $accountNumber;
		$this->type = $type;
	}

	/**
	 * Converts the object to a string
	 *
	 * @return string
	 */
	public function __toString()
	{
		return sprintf("%s: %s", get_class($this), $this->user);
	}
}
