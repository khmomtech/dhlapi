<?php

namespace Wk\DhlApiBundle\Model\B2b\Response;

use Wk\DhlApiBundle\Model\B2b\CreationState;
use Wk\DhlApiBundle\Model\B2b\StatusInformation;
use Wk\DhlApiBundle\Model\B2b\Version;

/**
 * Class CreateShipmentResponse
 *
 * @package Wk\DhlApiBundle\Model\B2b\Response
 */
class CreateShipmentResponse extends GetVersionResponse
{
    /**
     * @var Statusinformation $status
     * @access public
     */
    public $status = null;

    /**
     * @var CreationState $CreationState
     * @access public
     */
    public $CreationState = null;

    /**
     * Class constructor
     *
     * @param Version           $version
     * @param StatusInformation $status
     * @param CreationState     $creationState
     *
     * @access public
     */
    public function __construct(Version $version, StatusInformation $status, CreationState $creationState = null)
    {
        parent::__construct($version);

        $this->status = $status;
        $this->CreationState = $creationState;
    }

}
