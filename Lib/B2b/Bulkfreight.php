<?php

namespace Wk\DhlApiBundle\Lib\B2b;

class Bulkfreight
{

  /**
   * 
   * @var BulkfreightType $BulkfreightType
   * @access public
   */
  public $BulkfreightType = null;

  /**
   * 
   * @param BulkfreightType $BulkfreightType
   * @access public
   */
  public function __construct($BulkfreightType)
  {
    $this->BulkfreightType = $BulkfreightType;
  }

}
