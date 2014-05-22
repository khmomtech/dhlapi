<?php

namespace Wk\DhlApiBundle\Tests\Unit\B2b;

use DateTime;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Wk\DhlApiBundle\Lib\B2b\Connection;
use Wk\DhlApiBundle\Lib\B2b\IdentCode;
use Wk\DhlApiBundle\Model\B2b\Account;
use Wk\DhlApiBundle\Model\B2b\Attendance;
use Wk\DhlApiBundle\Model\B2b\CommunicationType;
use Wk\DhlApiBundle\Model\B2b\Company;
use Wk\DhlApiBundle\Model\B2b\CountryType;
use Wk\DhlApiBundle\Model\B2b\CreationState;
use Wk\DhlApiBundle\Model\B2b\NameType;
use Wk\DhlApiBundle\Model\B2b\NativeAddressType;
use Wk\DhlApiBundle\Model\B2b\Person;
use Wk\DhlApiBundle\Model\B2b\PickupAddressType;
use Wk\DhlApiBundle\Model\B2b\PickupBookingInformationType;
use Wk\DhlApiBundle\Model\B2b\PickupOrdererType;
use Wk\DhlApiBundle\Model\B2b\PieceInformation;
use Wk\DhlApiBundle\Model\B2b\ReceiverDDType;
use Wk\DhlApiBundle\Model\B2b\ReceiverTDType;
use Wk\DhlApiBundle\Model\B2b\Request\DeleteShipmentDDRequest;
use Wk\DhlApiBundle\Model\B2b\Response\DeleteShipmentResponse;
use Wk\DhlApiBundle\Model\B2b\Response\DoManifestResponse;
use Wk\DhlApiBundle\Model\B2b\Response\GetExportDocResponse;
use Wk\DhlApiBundle\Model\B2b\Response\GetLabelResponse;
use Wk\DhlApiBundle\Model\B2b\Response\GetManifestDDResponse;
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
use Wk\DhlApiBundle\Model\B2b\Response\CreateShipmentResponse;
use Wk\DhlApiBundle\Model\B2b\Response\UpdateShipmentResponse;
use Wk\DhlApiBundle\Model\B2b\Response\BookPickupResponse;

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
        $this->client = $this->getMockFromWsdl($wsdlFile, 'DhlApi', '', array(), true, $options);

        $logger = new Logger('DhlApi');

        $this->connection = new Connection();
        $this->connection->setLogger($logger);
    }

    /**
     * Tests creating a shipment (DD)
     *
     * @param ShipmentOrderDDType $shipmentOrder
     * @param CreateShipmentResponse $expectedResponse
     * @param int $expectedStatusCode
     * @dataProvider provideCreateShipmentDDData
     */
    public function testCreateShipmentDD(ShipmentOrderDDType $shipmentOrder, CreateShipmentResponse $expectedResponse, $expectedStatusCode)
    {
        $response = $this->connection->createShipmentDD($shipmentOrder);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

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
     * @param CreateShipmentResponse $expectedResponse
     * @param int $expectedStatusCode
     * @dataProvider provideCreateShipmentTDData
     */
    public function testCreateShipmentTD(ShipmentOrderTDType $shipmentOrder, CreateShipmentResponse $expectedResponse, $expectedStatusCode)
    {
        $response = $this->connection->createShipmentTD($shipmentOrder);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

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
     * @param UpdateShipmentResponse $expectedResponse
     * @param int $expectedStatusCode
     * @dataProvider provideUpdateShipmentDDData
     */
    public function testUpdateShipmentDD(ShipmentNumberType $shipmentNumber, ShipmentOrderDDType $shipmentOrder, UpdateShipmentResponse $expectedResponse, $expectedStatusCode)
    {
        $response = $this->connection->updateShipmentDD($shipmentNumber, $shipmentOrder);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

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
     * @param DeleteShipmentResponse $expectedResponse
     * @param int $expectedStatusCode
     * @dataProvider provideDeleteShipmentDDData
     */
    public function testDeleteShipmentDD(ShipmentNumberType $shipmentNumber, DeleteShipmentResponse $expectedResponse, $expectedStatusCode)
    {
        $response = $this->connection->deleteShipmentDD($shipmentNumber);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

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
     * @param DeleteShipmentResponse $expectedResponse
     * @param int $expectedStatusCode
     * @dataProvider provideDeleteShipmentTDData
     */
    public function testDeleteShipmentTD(ShipmentNumberType $shipmentNumber, DeleteShipmentResponse $expectedResponse, $expectedStatusCode)
    {
        $response = $this->connection->deleteShipmentTD($shipmentNumber);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

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
     * @param GetLabelResponse $expectedResponse
     * @param int $expectedStatusCode
     * @dataProvider provideGetLabelDDData
     */
    public function testGetLabelDD(ShipmentNumberType $shipmentNumber, GetLabelResponse $expectedResponse, $expectedStatusCode)
    {
        $response = $this->connection->getLabelDD($shipmentNumber);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

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
     * @param GetLabelResponse $expectedResponse
     * @param int $expectedStatusCode
     * @dataProvider provideGetLabelTDData
     */
    public function testGetLabelTD(ShipmentNumberType $shipmentNumber, GetLabelResponse $expectedResponse, $expectedStatusCode)
    {
        $response = $this->connection->getLabelTD($shipmentNumber);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->status);
        $this->assertSame($expectedStatusCode, $response->status->StatusCode);
    }

    /**
     * Tests booking a pickup
     *
     * @param BookPickupResponse  $expectedResponse
     * @param PickupBookingInformationType $bookingInformation
     * @param PickupAddressType $senderAddress
     * @param PickupOrdererType $ordererAddress
     * @dataProvider provideBookPickupData
     */
    public function testBookPickup(BookPickupResponse $expectedResponse, PickupBookingInformationType $bookingInformation, PickupAddressType $senderAddress, PickupOrdererType $ordererAddress = null)
    {
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
     * @param int $expectedStatusCode
     * @dataProvider provideCancelPickupData
     */
    public function testCancelPickup($confirmationNumber, $expectedStatusCode)
    {
        $response = $this->connection->cancelPickup($confirmationNumber);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

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
     * @param GetExportDocResponse $expectedResponse
     * @param int $expectedStatusCode
     * @dataProvider provideGetExportDocDDData
     */
    public function testGetExportDocDD(ShipmentNumberType $shipmentNumber, $docType, GetExportDocResponse $expectedResponse, $expectedStatusCode)
    {
        $response = $this->connection->getExportDocDD($shipmentNumber, $docType);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

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
     * @param GetExportDocResponse $expectedResponse
     * @param int $expectedStatusCode
     * @dataProvider provideGetExportDocTDData
     */
    public function testGetExportDocTD(ShipmentNumberType $shipmentNumber, $docType, GetExportDocResponse $expectedResponse, $expectedStatusCode)
    {
        $response = $this->connection->getExportDocTD($shipmentNumber, $docType);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

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
     * @param GetManifestDDResponse $expectedResponse
     * @param int $expectedStatusCode
     * @dataProvider provideGetManifestDDData
     */
    public function testGetManifestDD(DateTime $fromDate, DateTime $toDate, GetManifestDDResponse $expectedResponse, $expectedStatusCode)
    {
        $response = $this->connection->getManifestDD($fromDate, $toDate);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

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
     * @param DoManifestResponse $expectedResponse
     * @param int $expectedStatusCode
     * @dataProvider provideDoManifestDDData
     */
    public function testDoManifestDD(ShipmentNumberType $shipmentNumber, DoManifestResponse $expectedResponse, $expectedStatusCode)
    {
        $response = $this->connection->doManifestDD($shipmentNumber);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

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
     * @param DoManifestResponse $expectedResponse
     * @param int $expectedStatusCode
     * @dataProvider provideDoManifestTDData
     */
    public function testDoManifestTD(ShipmentNumberType $shipmentNumber, DoManifestResponse $expectedResponse, $expectedStatusCode)
    {
        $response = $this->connection->doManifestTD($shipmentNumber);

        // check if it's the expected response class
        $this->assertInstanceOf(get_class($expectedResponse), $response);

        // check if the response has a status information with the expected status code
        $this->assertObjectHasAttribute('Status', $response);
        $this->assertAttributeInstanceOf('StatusInformation', 'Status', $response);
        $this->assertObjectHasAttribute('StatusCode', $response->Status);
        $this->assertSame($expectedStatusCode, $response->Status->StatusCode);
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
            array($validOrder,   $successResponse, 200),
            array($invalidOrder, $errorResponse,   400),
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
            array($validOrder,   $successResponse, 200),
            array($invalidOrder, $errorResponse,   400),
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
     * Data provider to provide shipment number to update
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
            array($validOrder,   $successResponse, 200),
            array($invalidOrder, $errorResponse,   400),
        );
    }

}
