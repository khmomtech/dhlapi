<?php

namespace Wk\DhlApiBundle\Tests\Lib;

use DateTime;
use Wk\DhlApiBundle\Lib\B2b\Connection;
use Wk\DhlApiBundle\Model\B2b\Response\BookPickupResponse;
use Wk\DhlApiBundle\Model\B2b\CommunicationType;
use Wk\DhlApiBundle\Model\B2b\Company;
use Wk\DhlApiBundle\Model\B2b\CountryType;
use Wk\DhlApiBundle\Model\B2b\NameType;
use Wk\DhlApiBundle\Model\B2b\NativeAddressType;
use Wk\DhlApiBundle\Model\B2b\Person;
use Wk\DhlApiBundle\Model\B2b\PickupAddressType;
use Wk\DhlApiBundle\Model\B2b\PickupBookingInformationType;
use Wk\DhlApiBundle\Model\B2b\PickupOrdererType;
use Wk\DhlApiBundle\Model\B2b\StatusInformation;
use Wk\DhlApiBundle\Model\B2b\Version;
use Wk\DhlApiBundle\Model\B2b\ZipType;

/**
 * Class B2bConnectionTest
 * @package Wk\DhlApiBundle\Tests\Lib
 */
class B2bConnectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * Set up
     */
    public function setUp ()
    {
        // Create mock client from local WSDL file
        $this->client = $this->getMockFromWsdl(__DIR__ . '/../Files/geschaeftskundenversand-api-1.0.wsdl', 'SoapClient');

        $this->connection = new Connection();
        $this->connection->setClient($this->client);
    }

    /**
     * Tests booking and canceling a pickup
     *
     * @param PickupBookingInformationType $bookingInformation
     * @param PickupAddressType $senderAddress
     * @param PickupOrdererType $ordererAddress
     * @dataProvider providePickupData
     */
    public function testPickup(PickupBookingInformationType $bookingInformation, PickupAddressType $senderAddress, PickupOrdererType $ordererAddress)
    {
        $response = $this->connection->bookPickup($bookingInformation, $senderAddress, $ordererAddress);

        // check if it's the expected response class
        $this->assertInstanceOf('BookPickupResponse', $response);

        // check if the response has a status information with status code 0 (= successful)
        $this->assertObjectHasAttribute('Status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'Status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->Status);
        $this->assertSame(0, $response->Status->StatusCode);

        // afterwards use the confirmation number to cancel the pickup
        $response = $this->connection->cancelPickup($response->ConfirmationNumber);

        // check if it's the expected response class
        $this->assertInstanceOf('CancelPickupResponse', $response);

        // check if the response has a status information with status code 0 (= successful)
        $this->assertObjectHasAttribute('Status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'Status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->Status);
        $this->assertSame(0, $response->Status->StatusCode);
    }

    /**
     * Data provider to get booking information, sender address, orderer address and the expected response for a pickup
     *
     * @return array
     */
    public function providePickupData()
    {
        // Set up the booking information
        $earliestPickup = new DateTime('tomorrow 09:00');
        $latestPickup   = new DateTime('tomorrow 16:00');
        $information    = new PickupBookingInformationType('TDN', '5000000000', '01', $earliestPickup, $latestPickup, null, 'Hauptgebäude', 2, 0, 5, 2, 10, 15, 30, 5);

        // Set the addresses where to pick up and of the orderer
        $person         = new Person(null, 'Herr', 'Max', 'Moritz', 'Mustermann');
        $company        = new Company('Musterfirma GmbH', null);
        $name           = new NameType($person, $company);
        $country        = new CountryType('Germany', 'DE', null);
        $zip            = new ZipType('08150');
        $communication  = new CommunicationType('+493012345678', 'max@example.org', null, null, 'example.org', null);
        $address        = new NativeAddressType('Musterstraße', 1, null, $zip, 'Musterhausen', null, $country);
        $pickupAddress  = new PickupAddressType($name, $address, $communication);
        $ordererAddress = new PickupOrdererType($name, $address, $communication);

        return array(
            array($information, $pickupAddress, null),
            array($information, $pickupAddress, $ordererAddress),
        );
    }
}
