<?php

namespace Wk\DhlApiBundle\Model\B2b\Response;

use Wk\DhlApiBundle\Model\B2b\Version;
use Wk\DhlApiBundle\Model\B2b\LabelData;
use Wk\DhlApiBundle\Model\B2b\StatusInformation;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\Type;

/**
 * Class GetLabelResponse
 *
 * @package Wk\DhlApiBundle\Model\B2b\Response
 * @XmlRoot("response")
 */
class GetLabelResponse extends GetVersionResponse
{
    /**
     *
     * @var StatusInformation $status
     * @access public
     * @Type("Wk\DhlApiBundle\Model\B2b\StatusInformation")
     * @SerializedName("status")
     */
    public $status = null;

    /**
     *
     * @var LabelData $LabelData
     * @Type("Wk\DhlApiBundle\Model\B2b\LabelData")
     * @SerializedName("label")
     * @access public
     */
    public $LabelData = null;

    /**
     * Class constructor
     *
     * @param Version           $version
     * @param StatusInformation $status
     * @param LabelData         $labelData
     *
     * @access public
     */
    public function __construct(Version $version, StatusInformation $status, LabelData $labelData = null)
    {
        parent::__construct($version);

        $this->status = $status;
        $this->LabelData = $labelData;
    }
}
