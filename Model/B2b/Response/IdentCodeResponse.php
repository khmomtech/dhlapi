<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 13.05.14
 * Time: 14:27
 */

namespace Wk\DhlApiBundle\Model\B2b\Response;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\Type;

/**
 * Class IdentCodeResponse
 * @package Wk\DhlApiBundle\Model\B2b\Response
 * @XmlRoot("identcode")
 */
class IdentCodeResponse
{
    /**
     * @var string
     * @Type("string")
     * @SerializedName("raw")
     */
    private $raw;

    /**
     * @var string
     * @Type("string")
     * @SerializedName("formatted")
     */
    private $formatted;

    /**
     *  Constructor
     *
     * @param string $raw
     * @param string $formatted
     */
    public function __construct($raw, $formatted)
    {
        $this->raw = $raw;
        $this->formatted = $formatted;
    }

    /**
     * Getter for raw ident code
     *
     * @return string
     */
    public function getRaw()
    {
        return $this->raw;
    }

    /**
     * Setter for raw ident code
     *
     * @param string $raw
     */
    public function setRaw($raw)
    {
        $this->raw = $raw;
    }

    /**
     * Getter for formatted ident code
     *
     * @return string
     */
    public function getFormatted()
    {
        return $this->formatted;
    }

    /**
     * Setter for formatted ident code
     *
     * @param string $formatted
     */
    public function setFormatted($formatted)
    {
        $this->formatted = $formatted;
    }
}