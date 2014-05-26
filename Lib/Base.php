<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 26.05.14
 * Time: 17:28
 */

namespace Wk\DhlApiBundle\Lib;

/**
 * Class Base
 */
class Base
{
    /**
     * Converts the object to an array
     *
     * @return array
     */
    public function toArray()
    {
        return json_decode(json_encode($this), true);
    }
} 