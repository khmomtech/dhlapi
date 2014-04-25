<?php

namespace Wk\DhlApiBundle\Lib\B2b;

class Attendance
{

  /**
   * 
   * @var partnerID $partnerID
   * @access public
   */
  public $partnerID = null;

  /**
   * 
   * @param partnerID $partnerID
   * @access public
   */
  public function __construct($partnerID)
  {
    $this->partnerID = $partnerID;
  }

}
