<?php

namespace Wk\DhlApiBundle\Tests\Unit;

use DateTime;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Wk\DhlApiBundle\Lib\B2b\Connection;
use Wk\DhlApiBundle\Model\B2b\Attendance;
use Wk\DhlApiBundle\Model\B2b\CommunicationType;
use Wk\DhlApiBundle\Model\B2b\Company;
use Wk\DhlApiBundle\Model\B2b\CountryType;
use Wk\DhlApiBundle\Model\B2b\NameType;
use Wk\DhlApiBundle\Model\B2b\NativeAddressType;
use Wk\DhlApiBundle\Model\B2b\Person;
use Wk\DhlApiBundle\Model\B2b\PickupAddressType;
use Wk\DhlApiBundle\Model\B2b\PickupBookingInformationType;
use Wk\DhlApiBundle\Model\B2b\PickupOrdererType;
use Wk\DhlApiBundle\Model\B2b\ReceiverDDType;
use Wk\DhlApiBundle\Model\B2b\Response\CreateShipmentResponse;
use Wk\DhlApiBundle\Model\B2b\ShipmentDDType;
use Wk\DhlApiBundle\Model\B2b\ShipmentDetailsDDType;
use Wk\DhlApiBundle\Model\B2b\ShipmentItemDDType;
use Wk\DhlApiBundle\Model\B2b\ShipmentNumberType;
use Wk\DhlApiBundle\Model\B2b\ShipmentOrderDDType;
use Wk\DhlApiBundle\Model\B2b\ShipmentOrderTDType;
use Wk\DhlApiBundle\Model\B2b\ShipperDDType;
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
     * @var ContainerInterface
     */
    private $container;

    /**
     * Set up
     */
    public function setUp ()
    {
        // Get SOAP setting from container parameters
        $options = array(
            'trace'         => true,
            'encoding'      => 'UTF-8',
            'soap_version'  => SOAP_1_2,
            'login'         => 'geschaeftskunden_api',
            'password'      => 'Dhl_ep_test1',
            'location'      => 'https://cig.dhl.de/services/sandbox/soap',
        );

        // Create mock client from local WSDL file
        $wsdlFile = realpath(__DIR__ . '/../Resources/geschaeftskundenversand-api-1.0.wsdl');
        $this->client = $this->getMockFromWsdl($wsdlFile, 'SoapClient', '', array(), true, $options);

        $this->connection = new Connection();
        $this->connection->setClient($this->client);
    }

    /**
     * Tests creating a shipment (DD)
     *
     * @param ShipmentOrderDDType $shipmentOrder
     * @param string $responseClass
     * @param int $expectedStatusCode
     * @dataProvider provideCreateShipmentDDData
     */
    public function testCreateShipmentDD(ShipmentOrderDDType $shipmentOrder, $responseClass, $expectedStatusCode)
    {
        $response = $this->connection->createShipmentDD($shipmentOrder);

        // check if it's the expected response class
        $this->assertInstanceOf($responseClass, $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('Status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->status);
        $this->assertSame($expectedStatusCode, $response->status->StatusCode);
    }

    /**
     * Tests creating a shipment (TD)
     *
     * @param ShipmentOrderTDType $shipmentOrder
     * @param string $responseClass
     * @param int $expectedStatusCode
     * @dataProvider provideCreateShipmentTDData
     */
    public function testCreateShipmentTD(ShipmentOrderTDType $shipmentOrder, $responseClass, $expectedStatusCode)
    {
        $response = $this->connection->createShipmentTD($shipmentOrder);

        // check if it's the expected response class
        $this->assertInstanceOf($responseClass, $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('Status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->status);
        $this->assertSame($expectedStatusCode, $response->status->StatusCode);
    }

    /**
     * Tests updating a shipment (DD)
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param ShipmentOrderDDType $shipmentOrder
     * @param $responseClass
     * @param $expectedStatusCode
     * @dataProvider provideUpdateShipmentDDData
     */
    public function testUpdateShipmentDD(ShipmentNumberType $shipmentNumber, ShipmentOrderDDType $shipmentOrder, $responseClass, $expectedStatusCode)
    {
        $response = $this->connection->updateShipmentDD($shipmentNumber, $shipmentOrder);

        // check if it's the expected response class
        $this->assertInstanceOf($responseClass, $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->status);
        $this->assertSame($expectedStatusCode, $response->status->StatusCode);
    }

    /**
     * Tests deleting a shipment (DD)
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param $responseClass
     * @param $expectedStatusCode
     * @dataProvider provideDeleteShipmentDDData
     */
    public function testDeleteShipmentDD(ShipmentNumberType $shipmentNumber, $responseClass, $expectedStatusCode)
    {
        $response = $this->connection->deleteShipmentDD($shipmentNumber);

        // check if it's the expected response class
        $this->assertInstanceOf($responseClass, $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('Status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'Status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->Status);
        $this->assertSame($expectedStatusCode, $response->Status->StatusCode);
    }

    /**
     * Tests deleting a shipment (TD)
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param $responseClass
     * @param $expectedStatusCode
     * @dataProvider provideDeleteShipmentTDData
     */
    public function testDeleteShipmentTD(ShipmentNumberType $shipmentNumber, $responseClass, $expectedStatusCode)
    {
        $response = $this->connection->deleteShipmentTD($shipmentNumber);

        // check if it's the expected response class
        $this->assertInstanceOf($responseClass, $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('Status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'Status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->Status);
        $this->assertSame($expectedStatusCode, $response->Status->StatusCode);
    }

    /**
     * Tests getting a label (DD)
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param string $responseClass
     * @param int $expectedStatusCode
     * @dataProvider provideGetLabelDDData
     */
    public function testGetLabelDD(ShipmentNumberType $shipmentNumber, $responseClass, $expectedStatusCode)
    {
        $response = $this->connection->getLabelDD($shipmentNumber);

        // check if it's the expected response class
        $this->assertInstanceOf($responseClass, $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->status);
        $this->assertSame($expectedStatusCode, $response->status->StatusCode);
    }

    /**
     * Tests getting a label (TD)
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param string $responseClass
     * @param int $expectedStatusCode
     * @dataProvider provideGetLabelTDData
     */
    public function testGetLabelTD(ShipmentNumberType $shipmentNumber, $responseClass, $expectedStatusCode)
    {
        $response = $this->connection->getLabelTD($shipmentNumber);

        // check if it's the expected response class
        $this->assertInstanceOf($responseClass, $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->status);
        $this->assertSame($expectedStatusCode, $response->status->StatusCode);
    }

    /**
     * Tests booking a pickup
     *
     * @param PickupBookingInformationType $bookingInformation
     * @param PickupAddressType $senderAddress
     * @param PickupOrdererType $ordererAddress
     * @param string $responseClass
     * @param int $expectedStatusCode
     * @dataProvider provideBookPickupData
     */
    public function testBookPickup(PickupBookingInformationType $bookingInformation, PickupAddressType $senderAddress, PickupOrdererType $ordererAddress, $responseClass, $expectedStatusCode)
    {
        $response = $this->connection->bookPickup($bookingInformation, $senderAddress, $ordererAddress);

        // check if it's the expected response class
        $this->assertInstanceOf($responseClass, $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('Status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'Status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->Status);
        $this->assertSame($expectedStatusCode, $response->Status->StatusCode);
    }

    /**
     * Tests canceling a pickup
     *
     * @param string $confirmationNumber
     * @param int $expectedStatusCode
     * @dataProvider provideCancelPickupData
     */
    public function testCancelPickup($confirmationNumber, $expectedStatusCode)
    {
        $response = $this->connection->cancelPickup($confirmationNumber);

        // check if it's the expected response class
        $this->assertInstanceOf($responseClass, $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('Status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'Status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->Status);
        $this->assertSame($expectedStatusCode, $response->Status->StatusCode);
    }

    /**
     * Tests getting an export document (DD)
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param string $docType
     * @param string $responseClass
     * @param int $expectedStatusCode
     * @dataProvider provideGetExportDocDDData
     */
    public function testGetExportDocDD(ShipmentNumberType $shipmentNumber, $docType, $responseClass, $expectedStatusCode)
    {
        $response = $this->connection->getExportDocDD($shipmentNumber, $docType);

        // check if it's the expected response class
        $this->assertInstanceOf($responseClass, $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->status);
        $this->assertSame($expectedStatusCode, $response->status->StatusCode);
    }

    /**
     * Tests getting an export document (TD)
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param string $docType
     * @param string $responseClass
     * @param int $expectedStatusCode
     * @dataProvider provideGetExportDocTDData
     */
    public function testGetExportDocTD(ShipmentNumberType $shipmentNumber, $docType, $responseClass, $expectedStatusCode)
    {
        $response = $this->connection->getExportDocTD($shipmentNumber, $docType);

        // check if it's the expected response class
        $this->assertInstanceOf($responseClass, $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->status);
        $this->assertSame($expectedStatusCode, $response->status->StatusCode);
    }

    /**
     * Tests getting a manifest (DD)
     *
     * @param DateTime $fromDate
     * @param DateTime $toDate
     * @param string $responseClass
     * @param int $expectedStatusCode
     * @dataProvider provideGetManifestDDData
     */
    public function testGetManifestDD(DateTime $fromDate, DateTime $toDate, $responseClass, $expectedStatusCode)
    {
        $response = $this->connection->getManifestDD($fromDate, $toDate);

        // check if it's the expected response class
        $this->assertInstanceOf($responseClass, $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->status);
        $this->assertSame($expectedStatusCode, $response->status->StatusCode);
    }

    /**
     * Tests doing a manifest (DD)
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param string $responseClass
     * @param int $expectedStatusCode
     * @dataProvider provideDoManifestDDData
     */
    public function testDoManifestDD(ShipmentNumberType $shipmentNumber, $responseClass, $expectedStatusCode)
    {
        $response = $this->connection->doManifestDD($shipmentNumber);

        // check if it's the expected response class
        $this->assertInstanceOf($responseClass, $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('Status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'Status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->Status);
        $this->assertSame($expectedStatusCode, $response->Status->StatusCode);
    }

    /**
     * Tests doing a manifest (TD)
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param string $responseClass
     * @param int $expectedStatusCode
     * @dataProvider provideDoManifestTDData
     */
    public function testDoManifestTD(ShipmentNumberType $shipmentNumber, $responseClass, $expectedStatusCode)
    {
        $response = $this->connection->doManifestTD($shipmentNumber);

        // check if it's the expected response class
        $this->assertInstanceOf($responseClass, $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('Status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'Status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->Status);
        $this->assertSame($expectedStatusCode, $response->Status->StatusCode);
    }

    /**
     * Data provider to provide shipment order and the expected response for a shipment creation
     *
     * @return array
     */
    public function provideCreateShipmentDDData()
    {
        $attendance = new Attendance('01');
        $zip        = new ZipType('08150');
        $tomorrow   = new DateTime('tomorrow');
        $yesterday  = new DateTime('yesterday');

        // Create objects for valid requests
        $validItem      = new ShipmentItemDDType(10, 50, 30, 15);
        $validPerson    = new Person(null, 'Herr', 'Max', 'Moritz', 'Mustermann');
        $validCompany   = new Company('Musterfirma GmbH', null);
        $validName      = new NameType($validPerson, $validCompany);
        $validCountry   = new CountryType('Germany', 'DE', null);
        $validAddress   = new NativeAddressType('Musterstraße', '1', null, $zip, 'Musterhausen', null, $validCountry);
        $validComm      = new CommunicationType('+493012345678', 'max@example.org', null, null, 'example.org', null);
        $validShipper   = new ShipperDDType($validName, $validAddress, $validComm, '19');
        $validDetails   = new ShipmentDetailsDDType('EPN', $tomorrow, '5000000000', $attendance, $validItem, 300, 'EUR', '1234567890');
        $validReceiver  = new ReceiverDDType($validCompany, $validShipper, $validComm);
        $validShipment  = new ShipmentDDType($validDetails, $validShipper, $validReceiver);
        $validOrder     = new ShipmentOrderDDType(1, $validShipment);

        // Create objects for invalid requests
        $invalidDetails     = new ShipmentDetailsDDType('EPN', $yesterday, '5000000000', $attendance, $validItem, 300, 'EUR', '1234567890');
        $invalidShipment    = new ShipmentDDType($invalidDetails, $validShipper, $validReceiver);
        $invalidOrder       = new ShipmentOrderDDType(1, $invalidShipment);

        return array(
            array($validOrder,      'CreateShipmentResponse', 200),
            array($invalidOrder,    'CreateShipmentResponse', 400),
        );
    }

    /**
     * Data provider to provide booking information, sender address, orderer address and the expected response for a pickup booking
     *
     * @return array
     */
    public function provideBookPickupData()
    {
        // Set up the booking information
        $earliestPickup     = new DateTime('tomorrow 09:00');
        $latestPickup       = new DateTime('tomorrow 16:00');
        $validInformation   = new PickupBookingInformationType('TDN', '5000000000', '01', $earliestPickup, $latestPickup, null, 'Hauptgebäude', 2, 0, 5, 2, 10, 15, 30, 5);
        $invalidInformation = new PickupBookingInformationType('TDN', '5000000000', '01', $earliestPickup, $latestPickup, null, 'Hauptgebäude', 2, 0, 5, 2, 10, 15, 30, 5);

        // Set the addresses where to pick up and of the orderer
        $validPerson    = new Person(null, 'Herr', 'Max', 'Moritz', 'Mustermann');
        $invalidPerson  = new Person(null, 'Herr', null, 'Moritz', null);
        $company        = new Company('Musterfirma GmbH', null);
        $validName      = new NameType($validPerson, $company);
        $invalidName    = new NameType($invalidPerson, $company);
        $country        = new CountryType('Germany', 'DE', null);
        $zip            = new ZipType('08150');
        $communication  = new CommunicationType('+493012345678', 'max@example.org', null, null, 'example.org', null);
        $validAddress   = new NativeAddressType('Musterstraße', '1', null, $zip, 'Musterhausen', null, $country);
        $invalidAddress = new NativeAddressType(null, '1', null, $zip, 'Musterhausen', null, $country);
        $pickupAddress  = new PickupAddressType($validName, $validAddress, $communication);
        $invalidPickup  = new PickupAddressType($invalidName, $validAddress, $communication);
        $ordererAddress = new PickupOrdererType($validName, $validAddress, $communication);
        $invalidOrderer = new PickupOrdererType($validName, $invalidAddress, $communication);

        return array(
            array($validInformation,    $pickupAddress, null,            'BookPickupResponse', 200),
            array($validInformation,    $pickupAddress, $ordererAddress, 'BookPickupResponse', 200),
            array($validInformation,    null,           $ordererAddress, 'stdClass', 400),
            array($invalidInformation,  $pickupAddress, $ordererAddress, 'stdClass', 400),
            array($validInformation,    $invalidPickup, $ordererAddress, 'stdClass', 400),
            array($validInformation,    $invalidPickup, $invalidOrderer, 'stdClass', 400),
        );
    }
}
