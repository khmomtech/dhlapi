<?php

namespace Wk\DhlApiBundle\Tests\Unit\B2b;

use DateTime;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Wk\DhlApiBundle\Lib\B2b\Connection;
use Wk\DhlApiBundle\Model\B2b\Account;
use Wk\DhlApiBundle\Model\B2b\Attendance;
use Wk\DhlApiBundle\Model\B2b\CommunicationType;
use Wk\DhlApiBundle\Model\B2b\Company;
use Wk\DhlApiBundle\Model\B2b\CountryType;
use Wk\DhlApiBundle\Model\B2b\CreationState;
use Wk\DhlApiBundle\Model\B2b\DeletionState;
use Wk\DhlApiBundle\Model\B2b\ExportDocData;
use Wk\DhlApiBundle\Model\B2b\LabelData;
use Wk\DhlApiBundle\Model\B2b\ManifestState;
use Wk\DhlApiBundle\Model\B2b\NameType;
use Wk\DhlApiBundle\Model\B2b\NativeAddressType;
use Wk\DhlApiBundle\Model\B2b\Person;
use Wk\DhlApiBundle\Model\B2b\PickupAddressType;
use Wk\DhlApiBundle\Model\B2b\PickupBookingInformationType;
use Wk\DhlApiBundle\Model\B2b\PickupOrdererType;
use Wk\DhlApiBundle\Model\B2b\PieceInformation;
use Wk\DhlApiBundle\Model\B2b\ReceiverDDType;
use Wk\DhlApiBundle\Model\B2b\ReceiverTDType;
use Wk\DhlApiBundle\Model\B2b\Response\CancelPickupResponse;
use Wk\DhlApiBundle\Model\B2b\Response\DeleteShipmentResponse;
use Wk\DhlApiBundle\Model\B2b\Response\DoManifestResponse;
use Wk\DhlApiBundle\Model\B2b\Response\GetExportDocResponse;
use Wk\DhlApiBundle\Model\B2b\Response\GetLabelResponse;
use Wk\DhlApiBundle\Model\B2b\Response\GetManifestDDResponse;
use Wk\DhlApiBundle\Model\B2b\Response\CreateShipmentResponse;
use Wk\DhlApiBundle\Model\B2b\Response\UpdateShipmentResponse;
use Wk\DhlApiBundle\Model\B2b\Response\BookPickupResponse;
use Wk\DhlApiBundle\Model\B2b\ShipmentDDType;
use Wk\DhlApiBundle\Model\B2b\ShipmentTDType;
use Wk\DhlApiBundle\Model\B2b\ShipmentDetailsDDType;
use Wk\DhlApiBundle\Model\B2b\ShipmentDetailsTDType;
use Wk\DhlApiBundle\Model\B2b\ShipmentItemTDType;
use Wk\DhlApiBundle\Model\B2b\ShipmentItemDDType;
use Wk\DhlApiBundle\Model\B2b\ShipmentNumberType;
use Wk\DhlApiBundle\Model\B2b\ShipmentOrderDDType;
use Wk\DhlApiBundle\Model\B2b\ShipmentOrderTDType;
use Wk\DhlApiBundle\Model\B2b\ShipperTDType;
use Wk\DhlApiBundle\Model\B2b\ShipperDDType;
use Wk\DhlApiBundle\Model\B2b\StatusInformation;
use Wk\DhlApiBundle\Model\B2b\ZipType;

/**
 * Class ConnectionTest
 * @package Wk\DhlApiBundle\Tests\Unit\B2b
 */
class ConnectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $client;

    /**
     * Set up before each test
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
        $wsdlFile = realpath(__DIR__ . '/../../Resources/geschaeftskundenversand-api-1.0.wsdl');
        $this->client = $this->getMockFromWsdl($wsdlFile, 'DhlApi');

        $logger = new Logger('DhlApi');

        $this->connection = new Connection();
        $this->connection->setLogger($logger);
        $this->connection->setClient($this->client);
    }

    /**
     * Tests creating a shipment (DD)
     *
     * @param ShipmentOrderDDType $shipmentOrder
     * @param CreateShipmentResponse $expectedResponse
     *
     * @dataProvider provideCreateShipmentDDData
     */
    public function testCreateShipmentDD(ShipmentOrderDDType $shipmentOrder, CreateShipmentResponse $expectedResponse)
    {
        // Stubbing
        $this->client->expects($this->any())->method('createShipmentDD')->willReturn($expectedResponse);

        // Fire the function which is to be tested
        $response = $this->connection->createShipmentDD($shipmentOrder);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('Status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->status);
        $this->assertSame($expectedResponse->status->StatusCode, $response->status->StatusCode);
    }

    /**
     * Tests creating a shipment (TD)
     *
     * @param ShipmentOrderTDType $shipmentOrder
     * @param CreateShipmentResponse $expectedResponse
     *
     * @dataProvider provideCreateShipmentTDData
     */
    public function testCreateShipmentTD(ShipmentOrderTDType $shipmentOrder, CreateShipmentResponse $expectedResponse)
    {
        $response = $this->connection->createShipmentTD($shipmentOrder);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('Status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->status);
        $this->assertSame($expectedResponse->status->StatusCode, $response->status->StatusCode);
    }

    /**
     * Tests updating a shipment (DD)
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param ShipmentOrderDDType $shipmentOrder
     * @param UpdateShipmentResponse $expectedResponse
     *
     * @dataProvider provideUpdateShipmentDDData
     */
    public function testUpdateShipmentDD(ShipmentNumberType $shipmentNumber, ShipmentOrderDDType $shipmentOrder, UpdateShipmentResponse $expectedResponse)
    {
        // Stubbing
        $this->client->expects($this->any())->method('updateShipmentDD')->willReturn($expectedResponse);

        // Fire the function which is to be tested
        $response = $this->connection->updateShipmentDD($shipmentNumber, $shipmentOrder);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->status);
        $this->assertSame($expectedResponse->status->StatusCode, $response->status->StatusCode);
    }

    /**
     * Tests deleting a shipment (DD)
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param DeleteShipmentResponse $expectedResponse
     *
     * @dataProvider provideDeleteShipmentData
     */
    public function testDeleteShipmentDD(ShipmentNumberType $shipmentNumber, DeleteShipmentResponse $expectedResponse)
    {
        // Stubbing
        $this->client->expects($this->any())->method('deleteShipmentDD')->willReturn($expectedResponse);

        // Fire the function which is to be tested
        $response = $this->connection->deleteShipmentDD($shipmentNumber);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('Status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'Status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->Status);
        $this->assertSame($expectedResponse->Status->StatusCode, $response->Status->StatusCode);
    }

    /**
     * Tests deleting a shipment (TD)
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param DeleteShipmentResponse $expectedResponse
     *
     * @dataProvider provideDeleteShipmentData
     */
    public function testDeleteShipmentTD(ShipmentNumberType $shipmentNumber, DeleteShipmentResponse $expectedResponse)
    {
        // Stubbing
        $this->client->expects($this->any())->method('deleteShipmentTD')->willReturn($expectedResponse);

        // Fire the function which is to be tested
        $response = $this->connection->deleteShipmentTD($shipmentNumber);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('Status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'Status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->Status);
        $this->assertSame($expectedResponse->Status->StatusCode, $response->Status->StatusCode);
    }

    /**
     * Tests getting a label (DD)
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param GetLabelResponse $expectedResponse
     *
     * @dataProvider provideGetLabelData
     */
    public function testGetLabelDD(ShipmentNumberType $shipmentNumber, GetLabelResponse $expectedResponse)
    {
        // Stubbing
        $this->client->expects($this->any())->method('getLabelDD')->willReturn($expectedResponse);

        // Fire the function which is to be tested
        $response = $this->connection->getLabelDD($shipmentNumber);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->status);
        $this->assertSame($expectedResponse->status->StatusCode, $response->status->StatusCode);
    }

    /**
     * Tests getting a label (TD)
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param GetLabelResponse $expectedResponse
     *
     * @dataProvider provideGetLabelData
     */
    public function testGetLabelTD(ShipmentNumberType $shipmentNumber, GetLabelResponse $expectedResponse)
    {
        // Stubbing
        $this->client->expects($this->any())->method('getLabelTD')->willReturn($expectedResponse);

        // Fire the function which is to be tested
        $response = $this->connection->getLabelTD($shipmentNumber);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->status);
        $this->assertSame($expectedResponse->status->StatusCode, $response->status->StatusCode);
    }

    /**
     * Tests booking a pickup
     *
     * @param BookPickupResponse  $expectedResponse
     * @param PickupBookingInformationType $bookingInformation
     * @param PickupAddressType $senderAddress
     * @param PickupOrdererType $ordererAddress
     *
     * @dataProvider provideBookPickupData
     */
    public function testBookPickup(BookPickupResponse $expectedResponse, PickupBookingInformationType $bookingInformation, PickupAddressType $senderAddress, PickupOrdererType $ordererAddress = null)
    {
        // Stubbing
        $this->client->expects($this->any())->method('bookPickup')->willReturn($expectedResponse);

        // Fire the function which is to be tested
        $response = $this->connection->bookPickup($bookingInformation, $senderAddress, $ordererAddress);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('Status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'Status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->Status);
        $this->assertSame($expectedResponse->Status->StatusCode, $response->Status->StatusCode);
    }

    /**
     * Tests canceling a pickup
     *
     * @param string $confirmationNumber
     * @param CancelPickupResponse $expectedResponse
     *
     * @dataProvider provideCancelPickupData
     */
    public function testCancelPickup($confirmationNumber, CancelPickupResponse $expectedResponse)
    {
        // Stubbing
        $this->client->expects($this->any())->method('cancelPickup')->willReturn($expectedResponse);

        // Fire the function which is to be tested
        $response = $this->connection->cancelPickup($confirmationNumber);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('Status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'Status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->Status);
        $this->assertSame($expectedResponse->Status->StatusCode, $response->Status->StatusCode);
    }

    /**
     * Tests getting an export document (DD)
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param GetExportDocResponse $expectedResponse
     *
     * @dataProvider provideGetExportDocData
     */
    public function testGetExportDocDD(ShipmentNumberType $shipmentNumber, GetExportDocResponse $expectedResponse)
    {
        // Stubbing
        $this->client->expects($this->any())->method('getExportDocDD')->willReturn($expectedResponse);

        // Fire the function which is to be tested
        $response = $this->connection->getExportDocDD($shipmentNumber);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->status);
        $this->assertSame($expectedResponse->status->StatusCode, $response->status->StatusCode);
    }

    /**
     * Tests getting an export document (TD)
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param GetExportDocResponse $expectedResponse
     *
     * @dataProvider provideGetExportDocData
     */
    public function testGetExportDocTD(ShipmentNumberType $shipmentNumber, GetExportDocResponse $expectedResponse)
    {
        // Stubbing
        $this->client->expects($this->any())->method('getExportDocTD')->willReturn($expectedResponse);

        // Fire the function which is to be tested
        $response = $this->connection->getExportDocTD($shipmentNumber);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->status);
        $this->assertSame($expectedResponse->status->StatusCode, $response->status->StatusCode);
    }

    /**
     * Tests getting a manifest (DD)
     *
     * @param DateTime $fromDate
     * @param DateTime $toDate
     * @param GetManifestDDResponse $expectedResponse
     *
     * @dataProvider provideGetManifestDDData
     */
    public function testGetManifestDD(DateTime $fromDate, DateTime $toDate, GetManifestDDResponse $expectedResponse)
    {
        // Stubbing
        $this->client->expects($this->any())->method('getManifestDD')->willReturn($expectedResponse);

        // Fire the function which is to be tested
        $response = $this->connection->getManifestDD($fromDate, $toDate);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->status);
        $this->assertSame($expectedResponse->status->StatusCode, $response->status->StatusCode);
    }

    /**
     * Tests doing a manifest (DD)
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param DoManifestResponse $expectedResponse
     *
     * @dataProvider provideDoManifestData
     */
    public function testDoManifestDD(ShipmentNumberType $shipmentNumber, DoManifestResponse $expectedResponse)
    {
        // Stubbing
        $this->client->expects($this->any())->method('doManifestDD')->willReturn($expectedResponse);

        // Fire the function which is to be tested
        $response = $this->connection->doManifestDD($shipmentNumber);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('Status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'Status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->Status);
        $this->assertSame($expectedResponse->Status->StatusCode, $response->Status->StatusCode);
    }

    /**
     * Tests doing a manifest (TD)
     *
     * @param ShipmentNumberType $shipmentNumber
     * @param DoManifestResponse $expectedResponse
     *
     * @dataProvider provideDoManifestData
     */
    public function testDoManifestTD(ShipmentNumberType $shipmentNumber, DoManifestResponse $expectedResponse)
    {
        // Stubbing
        $this->client->expects($this->any())->method('doManifestTD')->willReturn($expectedResponse);

        // Fire the function which is to be tested
        $response = $this->connection->doManifestTD($shipmentNumber);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('Status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'Status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->Status);
        $this->assertSame($expectedResponse->Status->StatusCode, $response->Status->StatusCode);
    }

    /**
     * Data provider to provide day definite shipment order and the expected response for a shipment creation
     *
     * @return array
     */
    public function provideCreateShipmentDDData()
    {
        // Create misc
        $version    = Connection::getVersion();
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
        $validDetails   = new ShipmentDetailsDDType('EPN', $tomorrow, '5000000000', $attendance, $validItem);
        $validReceiver  = new ReceiverDDType($validCompany, $validShipper, $validComm);
        $validShipment  = new ShipmentDDType($validDetails, $validShipper, $validReceiver);
        $validOrder     = new ShipmentOrderDDType(1, $validShipment);

        // Create objects for invalid requests
        $invalidDetails     = new ShipmentDetailsDDType('EPN', $yesterday, '5000000000', $attendance, $validItem, 300, 'EUR', '1234567890');
        $invalidShipment    = new ShipmentDDType($invalidDetails, $validShipper, $validReceiver);
        $invalidOrder       = new ShipmentOrderDDType(1, $invalidShipment);

        /// Create response for success case
        $successNumber = new ShipmentNumberType('1234567890987654321');
        $successResponse = new CreateShipmentResponse(
            $version,
            new StatusInformation(0, 'ok'),
            new CreationState(0, 'ok', $validOrder->SequenceNumber, $successNumber, new PieceInformation($successNumber))
        );

        // Create response for error cases
        $errorResponse = new CreateShipmentResponse(
            $version,
            new StatusInformation(1000, 'General error')
        );

        return array(
            array($validOrder,   $successResponse),
            array($invalidOrder, $errorResponse),
        );
    }

    /**
     * Data provider to provide time definite shipment order and the expected response for a shipment creation
     *
     * @return array
     */
    public function provideCreateShipmentTDData()
    {
        // Create misc
        $version    = Connection::getVersion();
        $account    = new Account('5000000000');
        $zip        = new ZipType('08150');
        $tomorrow   = new DateTime('tomorrow');
        $yesterday  = new DateTime('yesterday');

        // Create objects for valid requests
        $validItem      = new ShipmentItemTDType(10, 50, 30, 15);
        $validPerson    = new Person(null, 'Herr', 'Max', 'Moritz', 'Mustermann');
        $validCompany   = new Company('Musterfirma GmbH', null);
        $validName      = new NameType($validPerson, $validCompany);
        $validCountry   = new CountryType('Germany', 'DE', null);
        $validAddress   = new NativeAddressType('Musterstraße', '1', null, $zip, 'Musterhausen', null, $validCountry);
        $validComm      = new CommunicationType('+493012345678', 'max@example.org', null, null, 'example.org', null);
        $validShipper   = new ShipperTDType($validName, $validAddress, $validComm, '19');
        $validDetails   = new ShipmentDetailsTDType('EPN', $tomorrow, $account, $validItem, 'Test shipment');
        $validReceiver  = new ReceiverTDType($validCompany, $validShipper, $validComm);
        $validShipment  = new ShipmentTDType($validDetails, $validShipper, $validReceiver);
        $validOrder     = new ShipmentOrderTDType(1, $validShipment);

        // Create objects for invalid requests
        $invalidDetails     = new ShipmentDetailsTDType('EPN', $yesterday, $account, $validItem, 300, 'EUR', '1234567890');
        $invalidShipment    = new ShipmentTDType($invalidDetails, $validShipper, $validReceiver);
        $invalidOrder       = new ShipmentOrderTDType(1, $invalidShipment);

        /// Create response for success case
        $successNumber = new ShipmentNumberType('1234567890987654321');
        $successResponse = new CreateShipmentResponse(
            $version,
            new StatusInformation(0, 'ok'),
            new CreationState(0, 'ok', $validOrder->SequenceNumber, $successNumber, new PieceInformation($successNumber))
        );

        // Create response for error cases
        $errorResponse = new CreateShipmentResponse(
            $version,
            new StatusInformation(1000, 'General error'),
            null
        );

        return array(
            array($validOrder,   $successResponse),
            array($invalidOrder, $errorResponse),
        );
    }

    /**
     * Data provider to provide booking information, sender address, orderer address and the expected response for a pickup booking
     *
     * @return array
     */
    public function provideBookPickupData()
    {
        // Misc
        $version = Connection::getVersion();

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

        // Create response for success case
        $successResponse = new BookPickupResponse($version, new StatusInformation(0, 'ok'), '1234567890987654321');

        // Create responses for the error cases
        $errorResponse = new BookPickupResponse($version, new StatusInformation(1000, 'General error'));

        return array(
            array($successResponse, $validInformation,   $pickupAddress, null),
            array($successResponse, $validInformation,   $pickupAddress, $ordererAddress),
            array($errorResponse,   $invalidInformation, $pickupAddress, $ordererAddress),
            array($errorResponse,   $validInformation,   $invalidPickup, $ordererAddress),
            array($errorResponse,   $validInformation,   $invalidPickup, $invalidOrderer),
        );
    }

    /**
     * Data provider to test canceling pickups
     *
     * @return array
     */
    public function provideCancelPickupData()
    {
        // Misc
        $version = Connection::getVersion();

        // Create respones for success cases
        $successResponse = new CancelPickupResponse($version, new StatusInformation(0, 'ok'));

        // Create respones for error cases
        $errorResponse = new CancelPickupResponse($version, new StatusInformation(1000, 'General error'));

        return array(
            array('917968580', $successResponse),
            array('9179685801', $errorResponse),
        );
    }

    /**
     * Data provider to provide shipment number and new data for shipment updates
     *
     * @return array
     */
    public function provideUpdateShipmentDDData()
    {
        // Create misc
        $version    = Connection::getVersion();
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
        $validDetails   = new ShipmentDetailsDDType('EPN', $tomorrow, '5000000000', $attendance, $validItem);
        $validReceiver  = new ReceiverDDType($validCompany, $validShipper, $validComm);
        $validShipment  = new ShipmentDDType($validDetails, $validShipper, $validReceiver);
        $validOrder     = new ShipmentOrderDDType(1, $validShipment);

        // Create objects for invalid requests
        $invalidDetails     = new ShipmentDetailsDDType('EPN', $yesterday, '5000000000', $attendance, $validItem, 300, 'EUR', '1234567890');
        $invalidShipment    = new ShipmentDDType($invalidDetails, $validShipper, $validReceiver);
        $invalidOrder       = new ShipmentOrderDDType(1, $invalidShipment);

        /// Create response for success case
        $successNumber = new ShipmentNumberType('1234567890987654321');
        $successResponse = new UpdateShipmentResponse(
            $version,
            new StatusInformation(0, 'ok'),
            new CreationState(0, 'ok', $validOrder->SequenceNumber, $successNumber, new PieceInformation($successNumber))
        );

        // Create response for error cases
        $errorResponse = new UpdateShipmentResponse(
            $version,
            new StatusInformation(1000, 'General error')
        );

        return array(
            array($successNumber, $validOrder, $successResponse),
            array($successNumber, $invalidOrder, $errorResponse),
        );
    }

    /**
     * Data provider to provide data to test deletion a shipment
     *
     * @return array
     */
    public function provideDeleteShipmentData()
    {
        // Misc
        $version = Connection::getVersion();

        // Create response for success case
        $successNumber   = new ShipmentNumberType('00340433836008760131');
        $successStatus   = new StatusInformation(0, 'ok');
        $successDeletion = new DeletionState($successNumber, $successStatus);
        $successResponse = new DeleteShipmentResponse($version, $successStatus, $successDeletion);

        // Create response for error case
        $errorNumber   = new ShipmentNumberType('00340433836008760132');
        $errorStatus   = new StatusInformation(1000, 'General error');
        $errorResponse = new DeleteShipmentResponse($version, $errorStatus);

        return array(
            array($successNumber, $successResponse),
            array($errorNumber, $errorResponse),
        );
    }

    /**
     * Data provider to provide data to test getting a label
     *
     * @return array
     */
    public function provideGetLabelData()
    {
        // Misc
        $version = Connection::getVersion();

        // Create response for success case
        $successNumber   = new ShipmentNumberType('00340433836008759937');
        $successStatus   = new StatusInformation(0, 'ok');
        $successData     = new LabelData($successNumber, $successStatus, 'http://test-intraship.dhl.com:80/cartridge.61/WSPrint?code=03EBF2F0F0383A4E689393267E5098E30314A0F71D32D6E7');
        $successResponse = new GetLabelResponse($version, $successStatus, $successData);

        // Create response for error case
        $errorNumber   = new ShipmentNumberType('00340433836008759938');
        $errorStatus   = new StatusInformation(1000, 'General error');
        $errorResponse = new GetLabelResponse($version, $errorStatus);

        return array(
            array($successNumber, $successResponse),
            array($errorNumber, $errorResponse),
        );
    }

    /**
     * Data provider to provide manifest data to test with
     *
     * @return array
     */
    public function provideGetManifestDDData()
    {
        // Misc
        $version    = Connection::getVersion();
        $today      = new DateTime('today');
        $tomorrow   = new DateTime('tomorrow');

        // Create response for success cases
        $successResponse = new GetManifestDDResponse(
            $version,
            new StatusInformation(0, 'ok'),
            null // TODO: use a sample PDF content (base64 encoded)
        );

        // Create response for error cases
        $errorResponse = new GetManifestDDResponse(
            $version,
            new StatusInformation(1000, 'General error')
        );

        return array(
            array($today, $tomorrow, $successResponse),
            array($tomorrow, $tomorrow,  $errorResponse),
        );
    }

	/**
	 * Data provider to test doing manifests
	 *
	 * @return array
	 */
	public function provideDoManifestData()
	{
		// Misc
		$version = Connection::getVersion();

		// Create response for success case
		$successNumber   = new ShipmentNumberType('00340433836008824970');
		$successStatus   = new StatusInformation(0, 'ok');
		$successManifest = new ManifestState($successNumber, $successStatus);
		$successResponse = new DoManifestResponse($version, $successStatus, $successManifest);

		// Create response for error case
		$errorNumber   = new ShipmentNumberType('00340433836008824971');
		$errorStatus   = new StatusInformation(1000, 'General error');
		$errorResponse = new DoManifestResponse($version, $errorStatus);

		return array(
			array($successNumber, $successResponse),
			array($errorNumber, $errorResponse),
		);
	}

	public function provideGetExportDocData()
	{
		// Misc
		$version = Connection::getVersion();

		// Create response for success case
		$successNumber   = new ShipmentNumberType('960701151320');
		$successStatus   = new StatusInformation(0, 'ok');
		$successData     = new ExportDocData($successNumber, $successStatus);
		$successResponse = new GetExportDocResponse($version, $successStatus, $successData);

		// Create response for error case
		$errorNumber   = new ShipmentNumberType('960701151321');
		$errorStatus   = new StatusInformation(1000, 'General error');
		$errorResponse = new GetExportDocResponse($version, $errorStatus);

		return array(
			array($successNumber, $successResponse),
			array($errorNumber, $errorResponse),
		);
	}
}
