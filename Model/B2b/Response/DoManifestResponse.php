<?php

namespace Wk\DhlApiBundle\Model\B2b\Response;

use Wk\DhlApiBundle\Model\B2b\Version;
use Wk\DhlApiBundle\Model\B2b\ManifestState;
use Wk\DhlApiBundle\Model\B2b\StatusInformation;

/**
 * Class DoManifestResponse
 *
 * @package Wk\DhlApiBundle\Model\B2b\Response
 */
class DoManifestResponse extends GetVersionResponse
{
    /**
     * @var StatusInformation $Status
     * @access public
     */
    public $Status = null;

    /**
     * @var ManifestState $ManifestState
     * @access public
     */
    public $ManifestState = null;

    /**
     * Class Constructor
     *
     * @param Version           $version
     * @param StatusInformation $status
     * @param ManifestState     $manifestState
     *
     * @access public
     */
    public function __construct(Version $version, StatusInformation $status, ManifestState $manifestState = null)
    {
        parent::__construct($version);

        $this->Status = $status;
        $this->ManifestState = $manifestState;
    }

}
