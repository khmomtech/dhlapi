<?php

namespace Wk\DhlApiBundle\Model\B2b\Response;

use Wk\DhlApiBundle\Model\B2b\Version;
use Wk\DhlApiBundle\Model\B2b\StatusInformation;

/**
 * Class GetManifestDDResponse
 *
 * @package Wk\DhlApiBundle\Model\B2b\Response
 */
class GetManifestDDResponse extends GetVersionResponse
{
    /**
     *
     * @var StatusInformation $status
     * @access public
     */
    public $status = null;

    /**
     *
     * @var string $ManifestPDFData
     * @access public
     */
    public $ManifestPDFData = null;

    /**
     * Class constructor
     *
     * @param Version           $version
     * @param StatusInformation $status
     * @param string            $manifestPDFData
     *
     * @access public
     */
    public function __construct(Version $version, StatusInformation $status, $manifestPDFData = null)
    {
        parent::__construct($version);

        $this->status = $status;
        $this->ManifestPDFData = $manifestPDFData;
    }

}
