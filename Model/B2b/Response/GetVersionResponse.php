<?php

namespace Wk\DhlApiBundle\Model\B2b\Response;

use Wk\DhlApiBundle\Model\B2b\Version;
use Wk\DhlApiBundle\Lib\Base;

/**
 * Class GetVersionResponse
 *
 * @package Wk\DhlApiBundle\Model\B2b\Response
 */
class GetVersionResponse extends Base
{
    /**
     *
     * @var Version $Version
     * @access public
     */
    public $Version = null;

    /**
     * Class constructor
     *
     * @param Version $version
     *
     * @access public
     */
    public function __construct(Version $version)
    {
        $this->Version = $version;
    }

}
