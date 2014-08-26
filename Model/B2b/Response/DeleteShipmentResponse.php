<?php

namespace Wk\DhlApiBundle\Model\B2b\Response;

use Wk\DhlApiBundle\Model\B2b\Version;
use Wk\DhlApiBundle\Model\B2b\DeletionState;
use Wk\DhlApiBundle\Model\B2b\StatusInformation;

/**
 * Class DeleteShipmentResponse
 *
 * @package Wk\DhlApiBundle\Model\B2b\Response
 */
class DeleteShipmentResponse extends GetVersionResponse
{
    /**
     *
     * @var StatusInformation $Status
     * @access public
     */
    public $Status = null;

    /**
     *
     * @var DeletionState $DeletionState
     * @access public
     */
    public $DeletionState = null;

    /**
     *
     * @param Version           $version
     * @param StatusInformation $status
     * @param DeletionState     $deletionState
     *
     * @access public
     */
    public function __construct(Version $version, StatusInformation $status, DeletionState $deletionState = null)
    {
        parent::__construct($version);

        $this->Status = $status;
        $this->DeletionState = $deletionState;
    }

}
