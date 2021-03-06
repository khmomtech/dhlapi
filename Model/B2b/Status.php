<?php

namespace Wk\DhlApiBundle\Model\B2b;

class Status
{

  /**
   * 
   * @var int $statuscode
   * @access public
   */
  public $statuscode = null;

  /**
   * 
   * @var string $statusDescription
   * @access public
   */
  public $statusDescription = null;

  /**
   * 
   * @param int $statusCode
   * @param string $statusDescription
   * @access public
   */
  public function __construct($statusCode, $statusDescription)
  {
    $this->statuscode = $statusCode;
    $this->statusDescription = $statusDescription;
  }

}
