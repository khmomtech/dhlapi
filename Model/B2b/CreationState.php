<?php

namespace Wk\DhlApiBundle\Model\B2b;

use JMS\Serializer\Annotation\Type;

/**
 * Class CreationState
 * @package Wk\DhlApiBundle\Model\B2b
 */
class CreationState
{
    /**
     *
     * @var int $StatusCode
     * @access public
     * @Type("integer")
     */
    public $StatusCode = null;

    /**
     *
     * @var string $StatusMessage
     * @access public
     * @Type("string")
     */
    public $StatusMessage = null;

    /**
     *
     * @var string $SequenceNumber
     * @access public
     * @Type("string")
     */
    public $SequenceNumber = null;

    /**
     *
     * @var ShipmentNumberType $ShipmentNumber
     * @access public
     * @Type("Wk\DhlApiBundle\Model\B2b\ShipmentNumberType")
     */
    public $ShipmentNumber = null;

    /**
     *
     * @var PieceInformation $PieceInformation
     * @access public
     * @Type("Wk\DhlApiBundle\Model\B2b\PieceInformation")
     */
    public $PieceInformation = null;

    /**
     *
     * @var string $Labelurl
     * @access public
     * @Type("string")
     */
    public $Labelurl = null;

    /**
     *
     * @var string $XMLLabel
     * @access public
     * @Type("string")
     */
    public $XMLLabel = null;

    /**
     *
     * @var string $PickupConfirmationNumber
     * @access public
     * @Type("string")
     */
    public $PickupConfirmationNumber = null;

    /**
     *
     * @param int $StatusCode
     * @param string $StatusMessage
     * @param string $SequenceNumber
     * @param ShipmentNumberType $ShipmentNumber
     * @param PieceInformation $PieceInformation
     * @param string $Labelurl
     * @param string $XMLLabel
     * @param string $PickupConfirmationNumber
     * @access public
     */
    public function __construct($StatusCode, $StatusMessage, $SequenceNumber, ShipmentNumberType $ShipmentNumber = null, PieceInformation $PieceInformation = null, $Labelurl = null, $XMLLabel = null, $PickupConfirmationNumber = null)
    {
        $this->StatusCode = $StatusCode;
        $this->StatusMessage = $StatusMessage;
        $this->SequenceNumber = $SequenceNumber;
        $this->ShipmentNumber = $ShipmentNumber;
        $this->PieceInformation = $PieceInformation;
        $this->Labelurl = $Labelurl;
        $this->XMLLabel = $XMLLabel;
        $this->PickupConfirmationNumber = $PickupConfirmationNumber;
    }
}
