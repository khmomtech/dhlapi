<?php

namespace Wk\DhlApiBundle\Model\B2b;

/**
 * Class StatusInformation
 *
 * @package Wk\DhlApiBundle\Model\B2b
 */
class StatusInformation
{
    /**
     *
     * @var int $StatusCode
     * @access public
     */
    public $StatusCode = null;

    /**
     *
     * @var string $StatusMessage
     * @access public
     */
    public $StatusMessage = null;

    /**
     *
     * @param int    $statusCode
     * @param string $statusMessage
     *
     * @access public
     */
    public function __construct($statusCode, $statusMessage)
    {
        $this->StatusCode = $statusCode;
        $this->StatusMessage = $statusMessage;
    }

}
