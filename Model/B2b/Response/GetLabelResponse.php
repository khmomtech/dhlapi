<?php

namespace Wk\DhlApiBundle\Model\B2b\Response;

use Wk\DhlApiBundle\Model\B2b\Version;
use Wk\DhlApiBundle\Model\B2b\LabelData;
use Wk\DhlApiBundle\Model\B2b\StatusInformation;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\Type;

/**
 * Class GetLabelResponse
 * @package Wk\DhlApiBundle\Model\B2b\Response
 * @XmlRoot("response")
 */
class GetLabelResponse
{

  /**
   * 
   * @var Version $Version
   * @access public
   * @Type("Wk\DhlApiBundle\Model\B2b\Version")
   * @SerializedName("version")
   */
  public $Version = null;

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
   * 
   * @param Version $Version
   * @param StatusInformation $status
   * @param LabelData $LabelData
   * @access public
   */
  public function __construct(Version $Version, StatusInformation $status, LabelData $LabelData)
  {
    $this->Version = $Version;
    $this->status = $status;
    $this->LabelData = $LabelData;
  }

}
