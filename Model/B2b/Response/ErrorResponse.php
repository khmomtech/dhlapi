<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 13.05.14
 * Time: 13:17
 */

namespace Wk\DhlApiBundle\Model\B2b\Response;

use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\XmlRoot;
use JMS\Serializer\Annotation\SerializedName;
use Wk\DhlApiBundle\Lib\Base;

/**
 * Class ErrorResponse
 *
 * @package Wk\DhlApiBundle\Lib
 * @XmlRoot("error")
 */
class ErrorResponse extends Base
{
    /**
     * @var int
     * @Type("integer")
     * @SerializedName("code")
     */
    private $code;

    /**
     * @var string
     * @Type("string")
     * @SerializedName("description")
     */
    private $description;

    /**
     * Constructor
     *
     * @param int    $code
     * @param string $description
     */
    public function __construct($code, $description)
    {
        $this->code = $code;
        $this->description = $description;
    }

    /**
     * Setter for error code
     *
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Getter for error code
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Setter for error description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Getter for error description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}