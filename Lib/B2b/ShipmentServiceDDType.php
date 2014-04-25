<?php

namespace Wk\DhlApiBundle\Lib\B2b;

class ShipmentServiceDDType
{

  /**
   * 
   * @var ServiceGroupDateTimeOptionDDType $ServiceGroupDateTimeOption
   * @access public
   */
  public $ServiceGroupDateTimeOption = null;

  /**
   * 
   * @var ServiceGroupIdentDDType $ShipmentServiceGroupIdent
   * @access public
   */
  public $ShipmentServiceGroupIdent = null;

  /**
   * 
   * @var ServiceGroupPickupDDType $ShipmentServiceGroupPickup
   * @access public
   */
  public $ShipmentServiceGroupPickup = null;

  /**
   * 
   * @var ServiceGroupBusinessPackInternationalDDType $ServiceGroupBusinessPackInternational
   * @access public
   */
  public $ServiceGroupBusinessPackInternational = null;

  /**
   * 
   * @var ServiceGroupDHLPaketDDType $ServiceGroupDHLPaket
   * @access public
   */
  public $ServiceGroupDHLPaket = null;

  /**
   * 
   * @var ServiceGroupOtherDDType $ServiceGroupOther
   * @access public
   */
  public $ServiceGroupOther = null;

  /**
   * 
   * @param ServiceGroupDateTimeOptionDDType $ServiceGroupDateTimeOption
   * @param ServiceGroupIdentDDType $ShipmentServiceGroupIdent
   * @param ServiceGroupPickupDDType $ShipmentServiceGroupPickup
   * @param ServiceGroupBusinessPackInternationalDDType $ServiceGroupBusinessPackInternational
   * @param ServiceGroupDHLPaketDDType $ServiceGroupDHLPaket
   * @param ServiceGroupOtherDDType $ServiceGroupOther
   * @access public
   */
  public function __construct($ServiceGroupDateTimeOption, $ShipmentServiceGroupIdent, $ShipmentServiceGroupPickup, $ServiceGroupBusinessPackInternational, $ServiceGroupDHLPaket, $ServiceGroupOther)
  {
    $this->ServiceGroupDateTimeOption = $ServiceGroupDateTimeOption;
    $this->ShipmentServiceGroupIdent = $ShipmentServiceGroupIdent;
    $this->ShipmentServiceGroupPickup = $ShipmentServiceGroupPickup;
    $this->ServiceGroupBusinessPackInternational = $ServiceGroupBusinessPackInternational;
    $this->ServiceGroupDHLPaket = $ServiceGroupDHLPaket;
    $this->ServiceGroupOther = $ServiceGroupOther;
  }

}
